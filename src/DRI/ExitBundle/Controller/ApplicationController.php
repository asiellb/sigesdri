<?php

namespace DRI\ExitBundle\Controller;


use Elastica\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\JsonResponse,
    Symfony\Component\HttpFoundation\BinaryFileResponse,
    Symfony\Component\HttpFoundation\ResponseHeaderBag,
    Symfony\Component\Security\Acl\Exception\Exception,
    Symfony\Component\Security\Core\Exception\AccessDeniedException,
    Symfony\Component\Serializer\Serializer,
    Symfony\Component\Serializer\Encoder\JsonEncoder,
    Symfony\Component\Serializer\Normalizer\ArrayDenormalizer,
    Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer,
    Symfony\Component\Serializer\Normalizer\ObjectNormalizer,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Doctrine\Common\Collections\ArrayCollection;

use Sg\DatatablesBundle\Datatable\DatatableInterface;

use PhpParser\Node\Scalar\String_;

use PhpOffice\PhpWord\PhpWord,
    PhpOffice\PhpWord\IOFactory,
    PhpOffice\PhpWord\Shared\Html,
    PhpOffice\PhpWord\Shared\Converter,
    PhpOffice\PhpWord\TemplateProcessor,
    PhpOffice\PhpWord\Settings as WordSettings,
    PhpOffice\PhpWord\Writer\Word2007\Element\Container,
    PhpOffice\Common\XMLWriter;

use HTMLtoOpenXML\Parser;

use DRI\ExitBundle\Entity\Application,
    DRI\ExitBundle\Entity\Mission,
    DRI\ExitBundle\Form\ApplicationType,
    DRI\ExitBundle\Datatables\ApplicationDatatable,
    DRI\UsefulBundle\Useful\Useful;

use DOMDocument;
use Vich\UploaderBundle\Exception\NoFileFoundException;

/**
 * Application controller.
 *
 * @Route("/application")
 */
class ApplicationController extends Controller
{
    /**
     * Lists all Application entities.
     * @param Request $request
     *
     * @Route("/index", name="exit_application_index")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.client');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(ApplicationDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DRIExitBundle:Application:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Displays a form to create a new Application entity.
     *
     * @Route("/new/{type}/{client}", name="exit_application_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST')")
     */
    public function newAction(Request $request, $type, $client = null)
    {
        $user = null;
        $holder = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $em = $this->getDoctrine()->getManager();
        $clientRepo = $em->getRepository('DRIClientBundle:Client');

        $exitApplication = new Application();

        if ($user){
            $exitApplication->setCreatedBy($user);
            $exitApplication->setLastUpdateBy($user);
        }

        if($client){

            $holder = $clientRepo->find($client);

            if(!$holder){
                throw $this->createNotFoundException("El cliente al que se le intenta crear la Solicitud de Salida no existe.");
            }
            $exitApplication->setClient($holder);
        }

        if($type) $exitApplication->setType($type);

        $clientType = $this->formatClientType($type);

        $form   = $this->createForm(ApplicationType::class, $exitApplication, [
            'currentAction' => 'new',
            'type' => $type
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($exitApplication);
            $em->flush();
            
            $editLink = $this->generateUrl('exit_application_edit', array('numberSlug' => $exitApplication->getNumberSlug()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se creó una nueva Solicitud de Salida para '$clientType'.</a>" );

            return $this->redirectToRoute('exit_application_edit', array('numberSlug' => $exitApplication->getNumberSlug()));
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('DRIExitBundle:Application:new.html.twig', array(
            'lastPage' => $lastVisited,
            'clientType' => $clientType,
            'client' => $holder,
            'exitApplication' => $exitApplication,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Application entity.
     *
     * @Route("/view/{numberSlug}", name="exit_application_show")
     * @Method("GET")
     */
    public function showAction(Application $exitApplication)
    {
        $type = $exitApplication->getType();
        $clientType = $this->formatClientType($type);

        $missions = $exitApplication->getMissions();

        $consultFile = array();
        $rtFile = array();

        foreach ($missions as $mission){
            switch($mission->getCountry()->getIso3()){
                case 'BOL': $consultFile[] = $mission;break;
                case 'NIC': $consultFile[] = $mission;break;
                case 'VEN': $consultFile[] = $mission;break;
                case 'COL': $rtFile[] = $mission;break;
                default:break;
            }

        }

        //var_dump($consultFile);

        $deleteForm = $this->createDeleteForm($exitApplication);
        return $this->render('DRIExitBundle:Application:show.html.twig', array(
            'clientType' => $clientType,
            'exitApplication' => $exitApplication,
            'consultFile' => $consultFile,
            'rtFile' => $rtFile,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Application entity.
     *
     * @Route("/edit/{numberSlug}", name="exit_application_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST')")
     */
    public function editAction(Request $request, Application $exitApplication)
    {
        $type = $exitApplication->getType();
        $clientType = $this->formatClientType($type);

        if (!$exitApplication->isClosed()){
            $user = null;

            if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $user = $this->getUser();  // get $user object
            }

            if ($user){
                $exitApplication->setLastUpdateBy($user);
            }

            $originalMissions = new ArrayCollection();
            $originalEconomics = new ArrayCollection();

            // Create an ArrayCollection of the current Tag objects in the database
            foreach ($exitApplication->getMissions() as $mission) {
                $originalMissions->add($mission);

                foreach ($mission->getEconomics() as $economic) {
                    $originalEconomics->add($economic);
                }
            }

            // Create an ArrayCollection of the current Tag objects in the database

            $deleteForm = $this->createDeleteForm($exitApplication);
            $editForm = $this->createForm('DRI\ExitBundle\Form\ApplicationType', $exitApplication, [
                'currentAction' => 'edit',
                'type' => $type
            ])->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();

                // remove the relationship between the economic and the Application
                foreach ($originalMissions as $mission) {
                    if (false === $exitApplication->getMissions()->contains($mission)) {
                        // remove the Task from the Tag
                        $em->remove($mission);
                    }

                    foreach ($originalEconomics as $economic) {
                        if (false === $mission->getEconomics()->contains($economic)) {
                            // remove the Task from the Tag
                            $em->remove($economic);
                        }
                    }
                }

                $em->persist($exitApplication);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'La Solicitud de Salida para '.$clientType.' se editó satisfactoriamente!');
                return $this->redirectToRoute('exit_application_edit', array('numberSlug' => $exitApplication->getNumberSlug()));
            }

            $lastVisited = $request->server->get('HTTP_REFERER');

            return $this->render('DRIExitBundle:Application:edit.html.twig', array(
                'lastPage' => $lastVisited,
                'clientType' => $clientType,
                'exitApplication' => $exitApplication,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else{
            $this->get('session')->getFlashBag()->add(
                'error',
                'La Solicitud de Salida par '.$clientType.' esta cerrada por lo que no se puede modificar.'
            );

            return $this->redirectToRoute('exit_application_show', array('numberSlug' => $exitApplication->getNumberSlug()));
        }
    }

    /**
     * Deletes a Application entity.
     *
     * @Route("/delete/{id}", name="exit_application_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Application $exitApplication)
    {
    
        $form = $this->createDeleteForm($exitApplication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($exitApplication);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Application was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Application');
        }
        
        return $this->redirectToRoute('exit_application_index');
    }
    
    /**
     * Creates a form to delete a Application entity.
     *
     * @param Application $exitApplication The Application entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Application $exitApplication)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('exit_application_delete', array('id' => $exitApplication->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Application by id
     *
     * @param mixed $id The entity id
     * @Route("/delete-by-id/{id}", name="exit_application_by_id_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function deleteByIdAction(Application $exitApplication){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($exitApplication);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Application was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Application');
        }

        return $this->redirect($this->generateUrl('exit_application_index'));

    }

    /**
     * Bulk delete action.
     *
     * @param Request $request
     *
     * @Route("/bulk/delete", name="exit_application_bulk_delete")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function bulkDeleteAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $choices = $request->request->get('data');
            $token = $request->request->get('token');

            if (!$this->isCsrfTokenValid('multiselect', $token)) {
                throw new AccessDeniedException('El token CSRF no es valido.');
            }

            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('DRIPassportBundle:Application');

            $counter = 0;

            foreach ($choices as $choice) {
                $entity = $repository->find($choice['id']);
                $em->remove($entity);
                $counter++;
            }

            $em->flush();

            return new Response('Se borraron satisfactoriamente '.$counter.' aplicaciones', 200);
        }


        return new Response('Bad Request', 400);
    }

    /**
     * Muestra información del cliente
     *
     * @param Request $request
     *
     * @Route("/", name="exit_application_client_show")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function clientShowAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest())
        {
            $encoders = array(new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);

            $client_id = $request->query->get('client_id');

            $client_id = (int)$client_id;

            $clientRepository = $em->getRepository('DRIClientBundle:Client')->findOneById($client_id);

            $client = [
                'clientArea'  =>  $clientRepository->getArea()->getName(),
                'clientPicture' =>  $clientRepository->getClientPicture(),
            ];
            $response = new JsonResponse();
            $response->setStatusCode(200);
            $response->setData(array(
                'response' => 'success',
                'client' => $serializer->serialize($client, 'json')
            ));
            return $response;
        }
    }

    /**
     * Assign numberSlug for Application entity.
     *
     * @Route("/assign_number_slug/dsa")
     * @Security("has_role('ROLE_ADMIN')")
     *
     */
    public function assignNumberSlug(){

        $em = $this->getDoctrine()->getManager();
        $eaRepo   = $em->getRepository('DRIExitBundle:Application');

        $eas = $eaRepo->findAll();

        foreach ($eas as $ea){
            $ea->setNumberSlug(Useful::getSlug($ea->getNumber()));

            $em->persist($ea);
            $em->flush();
        }
        $dondeEstaba = $this->getRequest()->server->get('HTTP_REFERER');
    }


    /**
     * Generate and save a PDF
     *
     * @Route("/pdf/{numberSlug}", name="client_pdf")
     */
    public function pdfAction(Application $exitApplication, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $app = $em->getRepository('DRIExitBundle:Application')->find($exitApplication);

        $html = $this->renderView('DRIExitBundle:Application:pdf_application.html.twig', [
            'app' => $app
        ]);

        $name = $app->getNumberSlug();

        $filename = sprintf('%s-%s.pdf', $name, date('Y-m-d'));

        $response = new Response (
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html,
                [
                    'images' => true,
                    'enable-javascript' => true,
                    'margin-left' => '15mm',
                    'margin-right' => '15mm',
                    'margin-top' => '15mm',
                    'margin-bottom' => '15mm',
                    //'orientation' => 'landscape',
                ]),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('inline; filename="%s"', $filename),
                //'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
        return $response;
    }


    /**
     * Generate and save a Comand File
     *
     * @Route("/command-file/{id}/word", name="exit_command_file_to_word")
     */
    public function commandFileToWordAction(Application $application, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $name = 'Ficha de Mandatos';
        $client = $application->getClient()->getShortName();
        $filename = sprintf('%s - %s - %s.docx', $name, $client, date('dmY'));
        $template = "report_templates/command_file_template.docx";

        $fullName = $application->getClient()->getFullName();
        if($application->getClient()->getWorkersPosition() != null){
            $position = $application->getClient()->getWorkersPosition();
        }else{
            $position = $application->getClient()->getWorkersOccupation();
        }

        $ipwActions = $application->getCommandFile()->getIpwActions();
        $mwoActions = $application->getCommandFile()->getMwoActions();
        $ittActions = $application->getCommandFile()->getIttActions();




        $parser = new Parser();
        $ooXml1 = $parser->fromHTML($ipwActions);
        $ooXml2 = $parser->fromHTML($mwoActions);
        $ooXml3 = $parser->fromHTML($ittActions);

        //throw new AccessDeniedException(var_dump($ooXml1));

        // ask the service for a Word2007
        $templateObj = $this->get('phpword')->createTemplateObject($template);

        $templateObj->setValue('fullName', $fullName);
        $templateObj->setValue('position', $position);
        $templateObj->setValue('ipwActions', $ooXml1);
        $templateObj->setValue('mwoActions', $ooXml2);
        $templateObj->setValue('ittActions', $ooXml3);

        $templateOut = $templateObj->save();

        $response = new BinaryFileResponse($templateOut);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }

    /**
     * Generate and save a Comand File
     *
     * @Route("/official-file/{id}/word", name="exit_official_file_to_word")
     */
    public function officialFileToWordAction(Application $application, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $name = 'Ficha Oficial';
        $client = $application->getClient()->getShortName();
        $filename = sprintf('%s - %s - %s.docx', $name, $client, date('dmY'));
        $template = "report_templates/official_application_file_template.docx";

        $currentDate = sprintf('%s', date('d/m/Y'));

        //BOSS DATA
        $area = $application->getClient()->getWorkersArea()->getName();
        $boss = $application->getProposedBy()->getFullName();
        if($application->getClient()->getWorkersPosition() != null){
            $bossPos = $application->getProposedBy()->getWorkersPosition();
        }else{
            $bossPos = $application->getProposedBy()->getWorkersOccupation();
        }

        //GENERAL DATA
        $name = $application->getClient()->getFullName();
        $ci = $application->getClient()->getCi();
        $age = Useful::getAge($application->getClient()->getBirthday()->format('Y-m-d'));
        $cd = $application->getClient()->getWorkersEduCategory();
        $gc = $application->getClient()->getWorkersSciGrade();
        if($application->getClient()->getWorkersPosition() != null){
            $pos = $application->getClient()->getWorkersPosition();
        }else{
            $pos = $application->getClient()->getWorkersOccupation();
        }

        if($application->getClient()->getWorkersAdmissionDate())
            $at = Useful::getAge($application->getClient()->getWorkersAdmissionDate()->format('Y-m-d')) ;
        else
            $at = '';

        //LAST 2 YEAR MISSIONS
        $last2Years =  array();

        //CURRENT MISSION
        $missionList = array();
        $economicList = array();
        $economicPIList = array();
        $missions = $application->getMissions();
        foreach ($missions as $mission){
            $economics = $mission->getEconomics();

            $missionList[] = [
                'country' => $mission->getCountry()->getSpName(),
                'institution' => $mission->getInstitution(),
                'concept' => $mission->getConcept(),
            ];
            if(($mission->getMonthlyPay() != null) && ($mission->getTotalPay() != null)){
                $economicPIList[] = [
                    'monthPay' => $mission->getMonthlyPay(),
                    'totalPay' => $mission->getTotalPay()
                ];
            }else{
                $economicPIList[] = [
                    'monthPay' => '',
                    'totalPay' => ''
                ];
            }

            foreach ($economics as $economic){
                $economicList[] = [
                    'eConcept' => $economic->getType(),
                    'eAmount' => $economic->getAmount(),
                    'eCurrency' => $economic->getCurrency(),
                    'eSource' => $economic->getSource()
                ];
            }
        }
        $timeAmount = $application->getLapsed();
        $exitDate = sprintf('%s', $application->getExitDate()->format('d/m/Y'));
        $arrivalDate = sprintf('%s', $application->getArrivalDate()->format('d/m/Y'));

        //CC INFORMATION
        $cSustitute = $application->getDirectiveSubstitute();
        $goeSustitute = $application->getGoeSubstitute();

        //ECONOMIC INFO 1
        $monthPay = '';
        $totalPay = '';

        //ECONOMIC INFO 2
        $economics = array();

        //LAST 2 YEARS DEPARTURES
        $departureList = array();
        $departures = $em->getRepository('DRIExitBundle:Application')->getDeparturesForClientInLastYears($application->getClient()->getId(),2);
        if(count($departures) > 0){
            foreach ($departures as $departure){
                $l2yMissions = $departure->getMissions();
                $l2yCountry = '';
                $l2yInstitution = '';
                $l2yConcept = '';
                $l2yLapsed = '';
                $l2yDepartureDate = '';

                $count = 0;
                foreach ($l2yMissions as $mission){
                    $count++;
                    if ($count == 1){
                        $l2yCountry .= $mission->getCountry()->getSpName();
                        $l2yInstitution .= $mission->getInstitution();
                        $l2yConcept .= $mission->getConcept();
                    }else{
                        $l2yCountry .= ' - '.$mission->getCountry()->getSpName();
                        $l2yInstitution .= ' - '.$mission->getInstitution();
                        $l2yConcept .= ' - '.$mission->getConcept();
                    }
                }

                $l2yLapsed = $departure->getLapsed();
                $l2yDepartureDate = $departure->getDeparture()->getDepartureDate();

                $departureList[] = [
                    'lastCountry' => $l2yCountry,
                    'lastInstitution' => $l2yInstitution,
                    'lastConcept' => $l2yConcept,
                    'lastLapsed' => $l2yLapsed,
                    'lastExitDate' => $l2yDepartureDate->format('d/m/Y')
                ];
            }
        }else{
            $departureList[] = [
                'lastCountry' => '',
                'lastInstitution' => '',
                'lastConcept' => '',
                'lastLapsed' => '',
                'lastExitDate' => ''
            ];
        }

        // ask the service for a Word2007
        $templateObj = $this->get('phpword')->createTemplateObject($template);

        $templateObj->setValue('currentDate', $currentDate  );
        $templateObj->setValue('area', $area);
        $templateObj->setValue('boss', $boss);
        $templateObj->setValue('bossPos', $bossPos);

        $templateObj->setValue('name', $name);
        $templateObj->setValue('ci', $ci);
        $templateObj->setValue('age', $age);
        $templateObj->setValue('cd', $cd);
        $templateObj->setValue('gc', $gc);
        $templateObj->setValue('pos', $pos);
        $templateObj->setValue('at', $at);

        $templateObj->cloneRowAndSetValues('country', $missionList);
        $templateObj->setValue('timeAmount', $timeAmount);
        $templateObj->setValue('exitDate', $exitDate);
        $templateObj->setValue('arrivalDate', $arrivalDate);

        $templateObj->setValue('cSustitute', $cSustitute);
        $templateObj->setValue('goeSustitute', $goeSustitute);

        $templateObj->cloneRowAndSetValues('monthPay', $economicPIList);

        $templateObj->cloneRowAndSetValues('eConcept', $economicList);

        $templateObj->cloneRowAndSetValues('lastCountry', $departureList);

        $templateOut = $templateObj->save();

        $response = new BinaryFileResponse($templateOut);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }

    /**
     * Generate and save a Comand File
     *
     * @Route("/cc-nomenclature-file/{id}/word", name="exit_cc_nomenclature_file_to_word")
     */
    public function ccNomenclatureFileToWordAction(Application $application, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $name = 'Ficha Nomenclatura CC';
        $client = $application->getClient()->getShortName();
        $filename = sprintf('%s - %s - %s.docx', $name, $client, date('dmY'));
        $template = "report_templates/cc_nomenclature_file_template.docx";

        $appDate = sprintf('%s', $application->getCreatedAt()->format('d/m/Y'));

        //BOSS DATA
        $area = $application->getClient()->getWorkersArea()->getName();

        //GENERAL DATA
        $clientName = $application->getClient()->getWorkersSciGrade().' '.$application->getClient()->getFullName();
        if($application->getClient()->getWorkersPosition() != null){
            $pos = $application->getClient()->getWorkersPosition();
        }else{
            $pos = $application->getClient()->getWorkersOccupation();
        }

        //CURRENT MISSION
        $missionList = array();
        $economicList = array();
        $economicPIList = array();
        $missions = $application->getMissions();
        $countries = '';
        $institutions = '';
        $inviteds = '';
        $objetives = '';
        $pi = '';
        $piYes = '';
        $piNo = '';
        $p = '';
        $e = '';
        $pa = '';
        $sm = '';
        $v = '';
        $d = '';
        $db = '';
        $i = '';
        $total = '';

        $countMissions = 0;
        foreach ($missions as $mission){
            $countMissions++;
            if ($countMissions == 1){
                $countries .= $mission->getCountry()->getSpName();
                $institutions .= $mission->getProvinceCountry().': '.$mission->getInstitution();
                $inviteds .= $mission->getPersonWhoInvitesName().': '.$mission->getPersonWhoInvitesPosition();
                $objetives .= $mission->getObjetives();
            }else{
                $countries .= ' - '.$mission->getCountry()->getSpName();
                $institutions .= ' - '.$mission->getProvinceCountry().': '.$mission->getInstitution();
                $inviteds .= ' - '.$mission->getPersonWhoInvitesName().': '.$mission->getPersonWhoInvitesPosition();
                $objetives .= ' - '.$mission->getObjetives();
            }

            switch ($mission->getConcept()){
                case 'ATE': $piYes = 'X';break;
                case 'COM': $piYes = 'X';break;
                default:break;
            }

            $economics = $mission->getEconomics();
            $p = '';
            $e = '';
            $pa = '';
            $sm = '';
            $v = '';
            $d = '';
            $db = '';
            $i = '';
            $ie = '';

            $countEconomics = 0;
            foreach ($economics as $economic){
                $countEconomics++;
                if ($countEconomics == 1){
                    switch ($economic->getType()){
                        case 'P':
                            if($p == ''){
                                $p = $economic->getSource();
                            }
                            if ($p != '' && $p != $economic->getSource()){
                                $p = 'MIXTA';
                            }
                            ;break;
                        case 'E':
                            if($e == ''){
                                $e = $economic->getSource();
                            }
                            if ($e != '' && $e != $economic->getSource()){
                                $e = 'MIXTA';
                            }
                            ;break;
                        case 'PA': $pa .= number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                        case 'SM': $sm .= number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                        case 'V': $v .= number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                        case 'D': $d .= number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                        case 'DB': $db .= number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                        case 'I': $i .= number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                        case 'IE': $ie .= number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                        default:break;
                    }
                }else{
                    switch ($economic->getType()){
                        case 'P':
                            if($p == ''){
                                $p = $economic->getSource();
                            }
                            if ($p != '' && $p != $economic->getSource()){
                                $p = 'MIXTA';
                            }
                            ;break;
                        case 'E':
                            if($e == ''){
                                $e = $economic->getSource();
                            }
                            if ($e != '' && $e != $economic->getSource()){
                                $e = 'MIXTA';
                            }
                            ;break;
                        case 'PA': $pa .= ' - '.number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                        case 'SM': $sm .= ' - '.number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                        case 'V': $v .= ' - '.number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                        case 'D': $d .= ' - '.number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                        case 'DB': $db .= ' - '.number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                        case 'I': $i .= ' - '.number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                        case 'IE': $ie .= ' - '.number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                        default:break;
                    }
                }
            }
        }
        if ($piYes != 'X')
            $piNo = 'X';

        $timeAmount = $application->getLapsed();
        $exitDate = sprintf('%s', $application->getExitDate()->format('d/m/Y'));
        $arrivalDate = sprintf('%s', $application->getArrivalDate()->format('d/m/Y'));

        $economics = array();

        //LAST 2 YEARS DEPARTURES
        $departureList = array();
        $departures = $em->getRepository('DRIExitBundle:Application')->getDeparturesForClientInLastYears($application->getClient()->getId());
        if(count($departures) > 0){
            foreach ($departures as $departure){
                $lyMissions = $departure->getMissions();
                $lyCountry = '';
                $lyLapsed = '';
                $lyLapsedDate = '';
                $lyResult = '';

                $count = 0;
                foreach ($lyMissions as $mission){
                    $count++;
                    if ($count == 1){
                        $lyCountry .= $mission->getCountry()->getSpName();
                    }else{
                        $lyCountry .= ' - '.$mission->getCountry()->getSpName();
                    }
                }

                $depObj = $departure->getDeparture();

                $lyLapsed = $departure->getLapsed();
                $lyLapsedDate = sprintf('%s - %s',$depObj->getDepartureDate()->format('d/m/Y'), $depObj->getReturnDate()->format('d/m/Y'));

                $lyResult = $depObj->getResults();

                $departureList[] = [
                    'lastCountry' => $lyCountry,
                    'lastLapsed' => $lyLapsed,
                    'lastLapsedRange' => $lyLapsedDate,
                    'lastResults' => $lyResult,
                ];
            }
        }else{
            $departureList[] = [
                'lastCountry' => '',
                'lastLapsed' => '',
                'lastLapsedRange' => '',
                'lastResults' => '',
            ];
        }
        $inPlan = '';
        $outPlan = '';
        if($application->getInPlan())
            $inPlan = 'X';
        else
            $outPlan = 'X';


        //var_dump($departureList);
        //throw new NotFoundException('prueba');
        // ask the service for a Word2007
        $templateObj = $this->get('phpword')->createTemplateObject($template);

        $templateObj->setValue('appDate', $appDate );
        $templateObj->setValue('client', $clientName);
        $templateObj->setValue('position', $pos);
        $templateObj->setValue('area', $area);

        $templateObj->setValue('countries', $countries);
        $templateObj->setValue('institutions', $institutions);
        $templateObj->setValue('inviteds', $inviteds);

        $templateObj->setValue('lapsed', $timeAmount);
        $templateObj->setValue('fromDate', $exitDate);
        $templateObj->setValue('untilDate', $arrivalDate);
        $templateObj->setValue('exitDate', $exitDate);

        $templateObj->setValue('objetives', $objetives);

        $templateObj->setValue('1', $piYes);
        $templateObj->setValue('2', $piNo);

        $templateObj->setValue('p', $p);
        $templateObj->setValue('e', $e);

        $templateObj->setValue('pa', $pa);
        $templateObj->setValue('sm', $sm);
        $templateObj->setValue('v', $v);
        $templateObj->setValue('d', $d);
        $templateObj->setValue('db', $db);
        $templateObj->setValue('i', $i);

        $templateObj->setValue('ie', $ie);

        $templateObj->cloneRowAndSetValues('lastCountry', $departureList);

        $templateObj->setValue('3', $inPlan);
        $templateObj->setValue('4', $outPlan);

        $templateOut = $templateObj->save();

        $response = new BinaryFileResponse($templateOut);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }

    /**
     * Generate and save a Comand File
     *
     * @Route("/consult-bol-nic-ven-file/{id}/word", name="exit_consult_bol_nic_ven_file_to_word")
     */
    public function consultBolNicVenFileToWordAction(Mission $mission, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $name = 'Ficha Consulta ';
        $countryIso3 = $mission->getCountry()->getIso3();
        $clientShortName = $mission->getApplication()->getClient()->getShortName();
        $filename = sprintf('%s%s - %s - %s.docx', $name, $countryIso3, $clientShortName, date('dmY'));
        $template = "report_templates/consult_bol_nic_ven_file_template.docx";

        //var_dump($mission);
        //throw new NotFoundException('prueba');

        $appDate = sprintf('%s', $mission->getApplication()->getCreatedAt()->format('d/m/Y'));
        $country = $mission->getCountry()->getSpName();
        $area = $mission->getApplication()->getClient()->getFaculty()->getName();

        $clientName = $mission->getApplication()->getClient()->getFullName();
        $gc = $mission->getApplication()->getClient()->getWorkersSciGrade();
        $cd = $mission->getApplication()->getClient()->getWorkersEduCategory();
        if($mission->getApplication()->getClient()->getWorkersPosition() != null){
            $pos = $mission->getApplication()->getClient()->getWorkersPosition();
        }else{
            $pos = $mission->getApplication()->getClient()->getWorkersOccupation();
        }

        $institution = $mission->getInstitution();
        $objetive = $mission->getObjetives();

        $piYes = '';
        $piNo = '';
        switch ($mission->getConcept()){
            case 'ATE': $piYes = 'X';break;
            case 'COM': $piYes = 'X';break;
            default: $piNo = 'X';break;
        }

        $exitDate = $mission->getFromDate()->format('d/m/Y');
        $lapsed = $mission->getTimeAmount();

        //var_dump($departureList);
        //throw new NotFoundException('prueba');
        // ask the service for a Word2007
        $templateObj = $this->get('phpword')->createTemplateObject($template);

        $templateObj->setValue('appDate', $appDate );
        $templateObj->setValue('country', $country);
        $templateObj->setValue('area', $area);
        $templateObj->setValue('client', $clientName);
        $templateObj->setValue('gc', $gc);
        $templateObj->setValue('cd', $cd);
        $templateObj->setValue('possition', $pos);

        $templateObj->setValue('institution', $institution);
        $templateObj->setValue('objetive', $objetive);

        $templateObj->setValue('1', $piYes);
        $templateObj->setValue('2', $piNo);

        $templateObj->setValue('exitDate', $exitDate);
        $templateObj->setValue('lapsed', $lapsed);

        $templateOut = $templateObj->save();

        $response = new BinaryFileResponse($templateOut);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }

    /**
     * Generate and save a Comand File
     *
     * @Route("/rt-nomenclature-file/{id}/word", name="exit_rt_nomenclature_file_to_word")
     */
    public function rtNomenclatureFileToWordAction(Mission $mission, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $application = $mission->getApplication();

        $name = 'Ficha Nomenclatura RT ';
        $clientShortName = $application->getClient()->getShortName();
        $countryIso3 = $mission->getCountry()->getIso3();
        $filename = sprintf('%s%s - %s - %s.docx', $name, $countryIso3, $clientShortName, date('dmY'));
        $template = "report_templates/rt_nomenclature_file_template.docx";

        $appDate = sprintf('%s', $application->getCreatedAt()->format('d/m/Y'));

        //BOSS DATA
        $area = $application->getClient()->getFaculty()->getName();

        //GENERAL DATA
        $clientName = $application->getClient()->getWorkersSciGrade().' '.$application->getClient()->getFullName();
        if($application->getClient()->getWorkersPosition() != null){
            $pos = $application->getClient()->getWorkersPosition();
        }else{
            $pos = $application->getClient()->getWorkersOccupation();
        }

        //CURRENT MISSION
        $country = $mission->getCountry()->getSpName();
        $institution = $mission->getProvinceCountry().': '.$mission->getInstitution();
        $invited = $mission->getPersonWhoInvitesName().': '.$mission->getPersonWhoInvitesPosition();

        $timeAmount = $mission->getTimeAmount();
        $fromDate = sprintf('%s', $mission->getFromDate()->format('d/m/Y'));
        $untilDate = sprintf('%s', $mission->getUntilDate()->format('d/m/Y'));

        $objetive = $mission->getObjetives();

        $piYes = '';
        $piNo = '';
        switch ($mission->getConcept()){
            case 'ATE': $piYes = 'X';break;
            case 'COM': $piYes = 'X';break;
            default: $piNo = 'X';break;
        }

        $economicList = array();
        $economicPIList = array();

        $p = '';
        $e = '';
        $pa = '';
        $sm = '';
        $v = '';
        $d = '';
        $db = '';
        $i = '';
        $ie = '';
        $total = '';

        $countEconomics = 0;
        foreach ($mission->getEconomics() as $economic){
            $countEconomics++;
            if ($countEconomics == 1){
                switch ($economic->getType()){
                    case 'P':
                        if($p == ''){
                            $p = $economic->getSource();
                        }
                        if ($p != '' && $p != $economic->getSource()){
                            $p = 'MIXTA';
                        }
                        ;break;
                    case 'E':
                        if($e == ''){
                            $e = $economic->getSource();
                        }
                        if ($e != '' && $e != $economic->getSource()){
                            $e = 'MIXTA';
                        }
                        ;break;
                    case 'PA': $pa .= number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                    case 'SM': $sm .= number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                    case 'V': $v .= number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                    case 'D': $d .= number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                    case 'DB': $db .= number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                    case 'I': $i .= number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                    case 'IE': $ie .= number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                    default:break;
                }
            }else{
                switch ($economic->getType()){
                    case 'P':
                        if($p == ''){
                            $p = $economic->getSource();
                        }
                        if ($p != '' && $p != $economic->getSource()){
                            $p = 'MIXTA';
                        }
                        ;break;
                    case 'E':
                        if($e == ''){
                            $e = $economic->getSource();
                        }
                        if ($e != '' && $e != $economic->getSource()){
                            $e = 'MIXTA';
                        }
                        ;break;
                    case 'PA': $pa .= ' - '.number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                    case 'SM': $sm .= ' - '.number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                    case 'V': $v .= ' - '.number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                    case 'D': $d .= ' - '.number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                    case 'DB': $db .= ' - '.number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                    case 'I': $i .= ' - '.number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                    case 'IE': $ie .= ' - '.number_format($economic->getAmount(),2,'.',',').$economic->getCurrency();break;
                    default:break;
                }
            }

        }

        //LAST YEARS DEPARTURES
        $departureList = array();
        $departures = $em->getRepository('DRIExitBundle:Application')->getDeparturesForClientInLastYears($application->getClient()->getId());
        if(count($departures) > 0){
            foreach ($departures as $departure){
                $lyMissions = $departure->getMissions();
                $lyCountry = '';
                $lyLapsed = '';
                $lyLapsedDate = '';
                $lyResult = '';

                $count = 0;
                foreach ($lyMissions as $mission){
                    $count++;
                    if ($count == 1){
                        $lyCountry .= $mission->getCountry()->getSpName();
                    }else{
                        $lyCountry .= ' - '.$mission->getCountry()->getSpName();
                    }
                }

                $depObj = $departure->getDeparture();

                $lyLapsed = $departure->getLapsed();
                $lyLapsedDate = sprintf('%s - %s',$depObj->getDepartureDate()->format('d/m/Y'), $depObj->getReturnDate()->format('d/m/Y'));

                $lyResult = $depObj->getResults();

                $departureList[] = [
                    'lastCountry' => $lyCountry,
                    'lastLapsed' => $lyLapsed,
                    'lastLapsedRange' => $lyLapsedDate,
                    'lastResults' => $lyResult,
                ];
            }
        }else{
            $departureList[] = [
                'lastCountry' => '',
                'lastLapsed' => '',
                'lastLapsedRange' => '',
                'lastResults' => '',
            ];
        }


        //var_dump($departureList);
        //throw new NotFoundException('prueba');
        // ask the service for a Word2007
        $templateObj = $this->get('phpword')->createTemplateObject($template);

        $templateObj->setValue('appDate', $appDate );
        $templateObj->setValue('client', $clientName);
        $templateObj->setValue('position', $pos);
        $templateObj->setValue('area', $area);

        $templateObj->setValue('country', $country);
        $templateObj->setValue('institution', $institution);
        $templateObj->setValue('invited', $invited);

        $templateObj->setValue('lapsed', $timeAmount);
        $templateObj->setValue('fromDate', $fromDate);
        $templateObj->setValue('untilDate', $untilDate);
        $templateObj->setValue('exitDate', $fromDate);

        $templateObj->setValue('objetive', $objetive);

        $templateObj->setValue('1', $piYes);
        $templateObj->setValue('2', $piNo);

        $templateObj->setValue('p', $p);
        $templateObj->setValue('e', $e);

        $templateObj->setValue('pa', $pa);
        $templateObj->setValue('sm', $sm);
        $templateObj->setValue('v', $v);
        $templateObj->setValue('d', $d);
        $templateObj->setValue('db', $db);
        $templateObj->setValue('i', $i);

        $templateObj->setValue('ie', $ie);

        $templateObj->cloneRowAndSetValues('lastCountry', $departureList);


        $templateOut = $templateObj->save();

        $response = new BinaryFileResponse($templateOut);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }

    public function formatClientType($type){
        switch ($type){
            case 'dir':
                return 'Directivo';break;
            case 'doc':
                return 'Docente';break;
            case 'nod':
                return 'No Docente';break;
            case 'est':
                return 'Estudiante';break;
            default:
                break;
        }
    }

}
