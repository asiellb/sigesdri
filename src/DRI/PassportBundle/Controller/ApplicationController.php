<?php

namespace DRI\PassportBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use PhpParser\Node\Scalar\String_;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sg\DatatablesBundle\Response\DatatableQueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

// Include the BinaryFileResponse and the ResponseHeaderBag
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

// Include the requires classes of Phpword
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\TemplateProcessor;

use DRI\PassportBundle\Datatables\ActivesApplicationDatatable;
use DRI\PassportBundle\Entity\Application;
use DRI\PassportBundle\Datatables\ApplicationDatatable;
use DRI\UsefulBundle\Useful\Useful;

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
     * @Route("/index", name="passport_application_index")
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

        return $this->render('DRIPassportBundle:Application:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Lists all Application entities.
     * @param Request $request
     *
     * @Route("/actives", name="passport_application_actives")
     * @Method("GET")
     *
     * @return Response
     */
    public function activesAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.client');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.datatable')->getDatatable('DRIPassportBundle:Application');
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);

            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();

            /** @var QueryBuilder $qb */
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('number = :state1');
            //$qb->andWhere('state = :state2');

            $qb->setParameter('state1', 'CON');
            //$qb->setParameter('state2', 'ENV');

            return $responseService->getResponse();
        }

        return $this->render('DRIPassportBundle:Application:actives.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Displays a form to create a new Application entity.
     *
     * @Route("/new/{client}", name="passport_application_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST')")
     */
    public function newAction(Request $request, $client = null)
    {
        $user = null;
        $holder = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $em = $this->getDoctrine()->getManager();
        $clientRepo = $em->getRepository('DRIClientBundle:Client');

        $application = new Application();

        if ($user){
            $application->setCreatedBy($user);
        }

        if($client){

            $holder = $clientRepo->find($client);

            if(!$holder){
                throw $this->createNotFoundException("El cliente al que se le intenta crear la Solicitud de Pasaporte no existe.");
            }
            $application->setClient($holder);
        }

        $form   = $this->createForm('DRI\PassportBundle\Form\ApplicationType', $application, [
            'currentAction' => 'new'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($application);
            $em->flush();
            
            $editLink = $this->generateUrl('passport_application_edit', array('numberSlug' => $application->getNumberSlug()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se creó una nueva Solicitud de Pasaporte.</a>" );

            return $this->redirectToRoute('passport_application_show', [
                'numberSlug' => $application->getNumberSlug(),
            ]);
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('@DRIPassport/Application/new.html.twig', array(
            'lastPage' => $lastVisited,
            'client' => $holder,
            'application' => $application,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Application entity.
     *
     * @Route("/view/{numberSlug}", name="passport_application_show")
     * @Method("GET")
     */
    public function showAction(Application $application)
    {
        $deleteForm = $this->createDeleteForm($application);
        return $this->render('@DRIPassport/Application/show.html.twig', array(
            'application' => $application,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing application entity.
     *
     * @Route("/edit/{numberSlug}", name="passport_application_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST')")
     */
    public function editAction(Request $request, Application $application)
    {
        if (!$application->isClosed()){
            $user = null;

            if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $user = $this->getUser();  // get $user object
            }

            if ($user){
                $application->setLastUpdateBy($user);
            }

            $deleteForm = $this->createDeleteForm($application);
            $editForm = $this->createForm('DRI\PassportBundle\Form\ApplicationType', $application, [
                'currentAction' => 'edit'
            ])->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($application);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'La Solicitud de Pasaporte se editó satisfactoriamente!');
                return $this->redirectToRoute('passport_application_show', array('numberSlug' => $application->getNumberSlug()));
            }

            $lastVisited = $request->server->get('HTTP_REFERER');

            return $this->render('@DRIPassport/Application/edit.html.twig', array(
                'lastPage' => $lastVisited,
                'application' => $application,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else{
            $this->get('session')->getFlashBag()->add(
                'error',
                'La Solicitud de Pasaporte esta cerrada por lo que no se puede modificar.'
            );

            return $this->redirectToRoute('passport_application_show', array('numberSlug' => $application->getNumberSlug()));
        }

    }

    /**
     * Deletes a Application entity.
     *
     * @Route("/{id}", name="passport_application_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Application $application)
    {
    
        $form = $this->createDeleteForm($application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($application);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'La Solicitud se borro satisfactoriamente!');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Existe un problema para borrar la Solicitud');
        }
        
        return $this->redirectToRoute('passport_application_index');
    }
    
    /**
     * Creates a form to delete a Application entity.
     *
     * @param Application $application The Application entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Application $application)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('passport_application_delete', array('id' => $application->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Application by id
     *
     * @param mixed $id The entity id
     *
     * @Route("/delete/{id}", name="passport_application_by_id_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function deleteByIdAction(Application $application){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($application);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Application was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Application');
        }

        return $this->redirect($this->generateUrl('passport_application_index'));

    }

    /**
     * Bulk delete action.
     *
     * @param Request $request
     *
     * @Route("/bulk/delete", name="passport_application_bulk_delete")
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
     * Assign numberSlug for Application entity.
     *
     * @Route("/assign_application_number/")
     * @Security("has_role('ROLE_ADMIN')")
     *
     */
    public function assignApplicationNumberSlug(){

        $em = $this->getDoctrine()->getManager();
        $passAppRepo   = $em->getRepository('DRIPassportBundle:Application');

        $applications = $passAppRepo->findByApplicationNumberSlug('');

        foreach ($applications as $application){
            $application->setApplicationNumberSlug(Useful::getSlug($application->getNumber()));

            $em->persist($application);
            $em->flush();
        }
    }


    /**
     * Generate and save a ManagerTravelPlan to Word
     *
     * @Route("/to/word/{numberSlug}", name="passport_application_to_word")
     */
    public function applicationToWordAction(Application $application)
    {
        $em = $this->getDoctrine()->getManager();
        $currentDate = sprintf('%s', date('d/m/Y'));
        $name = 'Solicitud de Pasaporte ';
        $filename = sprintf('%s - %s.docx', $name, $application->getNumber());
        $template = "report_templates/passport_application_template.docx";

        //Vars declaration
        $reasonConfection   = ''; $reasonExtension    = '';
        if ($application->getReason() == 'CON'){
            $reasonConfection = 'X';
        }elseif ($application->getReason() == 'PRO'){
            $reasonExtension = 'X';
        }

        $ci = $application->getClient()->getCi();
        $applicationDate = sprintf('%s', date_format($application->getApplicationDate(), 'd/m/Y'));

        $firstLastName = $application->getClient()->getFirstLastName();
        $secondLastName = $application->getClient()->getSecondLastName();
        $firstName = $application->getClient()->getFirstName();
        $secondName = $application->getClient()->getSecondName();
        $birthday = sprintf('%s', date_format($application->getClient()->getBirthday(), 'd/m/Y'));

        $genderF = ''; $genderM = '';
        if ($application->getClient()->getGender() == 'F'){
            $genderF = 'X';
        }elseif ($application->getClient()->getGender() == 'M'){
            $genderM = 'X';
        }

        $passportTypeCOR = ''; $passportTypeDIP = ''; $passportTypeOFI = ''; $passportTypeSER = ''; $passportTypeMAR = '';
        switch ($application->getPassportType()){
            case 'COR': $passportTypeCOR = 'X'; break;
            case 'DIP': $passportTypeDIP = 'X'; break;
            case 'OFI': $passportTypeOFI = 'X'; break;
            case 'SER': $passportTypeSER = 'X'; break;
            case 'MAR': $passportTypeMAR = 'X'; break;
            default: '-';break;
        }

        $appTypeREG = ''; $appTypeINM = '';
        switch ($application->getApplicationType()){
            case 'REG': $appTypeREG = 'X'; break;
            case 'INM': $appTypeINM = 'X'; break;
            default: '-';break;
        }

        $organ = $application->getOrgan();
        $travelReason = $application->getTravelReason();

        $mothesrsName = $application->getClient()->getMothersName();
        $fathersName = $application->getClient()->getFathersName();

        $height = $application->getClient()->getHeight();

        $pvs = $application->getClient()->getPvs();

        $eyesColorC = '';$eyesColorN = '';$eyesColorP = '';
        switch ($application->getClient()->getEyesColor()){
            case 'Claros': $eyesColorC = 'X'; break;
            case 'Negros': $eyesColorN = 'X'; break;
            case 'Pardos': $eyesColorP = 'X'; break;
            default: '-';break;
        }

        $skinColorB = '';$skinColorN = '';$skinColorAm = '';$skinColorM = '';$skinColorAl = '';
        switch ($application->getClient()->getSkinColor()){
            case 'Blanca'   : $skinColorB   = 'X'; break;
            case 'Negra'    : $skinColorN   = 'X'; break;
            case 'Amarilla' : $skinColorAm  = 'X'; break;
            case 'Mulata'   : $skinColorM   = 'X'; break;
            case 'Albina'   : $skinColorAl  = 'X'; break;
            default: '-';break;
        }

        $hairColorCn = '';$hairColorCs = '';$hairColorN = '';$hairColorRo = '';$hairColorRu = '';$hairColorO = '';
        switch ($application->getClient()->getHairColor()){
            case 'Canoso'   : $hairColorCn  = 'X'; break;
            case 'Castaño'  : $hairColorCs  = 'X'; break;
            case 'Negro'    : $hairColorN   = 'X'; break;
            case 'Rojo'     : $hairColorRo  = 'X'; break;
            case 'Rubio'    : $hairColorRu  = 'X'; break;
            case 'Otros'    : $hairColorO   = 'X'; break;
            default: '-';break;
        }

        $countryBirth = $application->getClient()->getCountryBirth();
        $stateBirth = $application->getClient()->getStateBirth();
        $cityBirth = $application->getClient()->getCityBirth();
        $foreingCityBirth = $application->getClient()->getForeignCityBirth();
        $citizenship = $application->getClient()->getCitizenship();

        $workersOccupation = $application->getClient()->getWorkersOccupation();

        $country = $application->getClient()->getCountry()->getSpName();
        $state = $application->getClient()->getState();
        $city = $application->getClient()->getCity();
        $street = $application->getClient()->getStreet();
        $highway = $application->getClient()->getHighway();
        $firstBetween = $application->getClient()->getFirstBetween();
        $secondBetween = $application->getClient()->getSecongBetween();
        $number = $application->getClient()->getNumber();
        $km = $application->getClient()->getKm();
        $apartment = $application->getClient()->getApartment();
        $cpa = $application->getClient()->getCpa();
        $farm = $application->getClient()->getFarm();
        $town = $application->getClient()->getTown();
        $circunscription = $application->getClient()->getCircunscription();


        //var_dump($agreementsArray);
        // ask the service for a Word2007
        $templateObj = $this->get('phpword')->createTemplateObject($template);

        $templateObj->setValue('1', $reasonConfection);
        $templateObj->setValue('2', $reasonExtension);

        $templateObj->setValue('ci', $ci);
        $templateObj->setValue('applicationDate', $applicationDate);

        $templateObj->setValue('firstLastName', $firstLastName);
        $templateObj->setValue('secondLastName', $secondLastName);
        $templateObj->setValue('firstName', $firstName);
        $templateObj->setValue('secondName', $secondName);
        $templateObj->setValue('birthday', $birthday);

        $templateObj->setValue('3', $genderM);
        $templateObj->setValue('4', $genderF);

        $templateObj->setValue('5', $passportTypeCOR);
        $templateObj->setValue('6', $passportTypeDIP);
        $templateObj->setValue('7', $passportTypeOFI);
        $templateObj->setValue('8', $passportTypeSER);
        $templateObj->setValue('9', $passportTypeMAR);

        $templateObj->setValue('10', $appTypeREG);
        $templateObj->setValue('11', $appTypeINM);

        $templateObj->setValue('organ', $organ);
        $templateObj->setValue('travelReason', $travelReason);

        $templateObj->setValue('mothersName', $mothesrsName);
        $templateObj->setValue('fathersName', $fathersName);

        $templateObj->setValue('height', $height);

        $templateObj->setValue('pvs', $pvs);

        $templateObj->setValue('12', $eyesColorC);
        $templateObj->setValue('13', $eyesColorN);
        $templateObj->setValue('14', $eyesColorP);

        $templateObj->setValue('15', $skinColorB);
        $templateObj->setValue('16', $skinColorN);
        $templateObj->setValue('17', $skinColorAm);
        $templateObj->setValue('18', $skinColorM);
        $templateObj->setValue('19', $skinColorAl);

        $templateObj->setValue('20', $hairColorCn);
        $templateObj->setValue('21', $hairColorCs);
        $templateObj->setValue('22', $hairColorN);
        $templateObj->setValue('23', $hairColorRo);
        $templateObj->setValue('24', $hairColorRu);
        $templateObj->setValue('25', $hairColorO);

        $templateObj->setValue('countryBirth', $countryBirth);
        $templateObj->setValue('stateBirth', $stateBirth);
        $templateObj->setValue('cityBirth', $cityBirth);
        $templateObj->setValue('foreignCityBirth', $foreingCityBirth);
        $templateObj->setValue('citizenship', $citizenship);

        $templateObj->setValue('workersOccupation', $workersOccupation);

        $templateObj->setValue('country', $country);
        $templateObj->setValue('state', $state);
        $templateObj->setValue('city', $city);
        $templateObj->setValue('street', $street);
        $templateObj->setValue('highway', $highway);
        $templateObj->setValue('firstBetween', $firstBetween);
        $templateObj->setValue('secondBetween', $secondBetween);
        $templateObj->setValue('number', $number);
        $templateObj->setValue('km', $km);
        $templateObj->setValue('apartment', $apartment);
        $templateObj->setValue('cpa', $cpa);
        $templateObj->setValue('farm', $farm);
        $templateObj->setValue('town', $town);
        $templateObj->setValue('circunscription', $circunscription);

        $templateOut = $templateObj->save();

        $response = new BinaryFileResponse($templateOut);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }
}
