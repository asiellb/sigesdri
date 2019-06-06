<?php

namespace DRI\AgreementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Form\FormInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use DRI\AgreementBundle\Entity\Institutional;
use DRI\AgreementBundle\Entity\Application;


/**
 * Institutional controller.
 *
 * @Route("/institutional")
 */
class InstitutionalController extends Controller
{
    /**
     * Lists all Institutional entities.
     *
     * @Route("/index", name="agreement_institutional_index", methods={"GET"})
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('DRIAgreementBundle:Institutional:index.html.twig');
    }

    /**
     * Lists all Institutional Agreements entities.
     *
     * @param Request $request
     * @Route("/list", name="agreement_institutional_list", methods={"GET"})
     * @return Response
     * @throws \Exception
     */
    public function listAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('app.datatable.agreement.institutional');
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DRIAgreementBundle:Institutional:list.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Displays a form to create a new Institutional entity.
     *
     * @param Request $request
     * @param Application $application
     * @Route("/new/{application}", name="agreement_institutional_new", methods={"GET", "POST"})
     * @Security("has_role('ROLE_MANAGE_SPECIALIST')")
     * @return Response
     */
    public function newAction(Request $request, Application $application = null)
    {
        $user   = null;
        $app    = null;
        $inst   = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $em = $this->getDoctrine()->getManager();
        $applicationRepo = $em->getRepository('DRIAgreementBundle:Application');
        $institutionRepo = $em->getRepository('DRIAgreementBundle:Institution');

        $institutional = new Institutional();

        if ($user){
            $institutional->setCreatedBy($user);
        }

        if($application){

            $app    = $applicationRepo->find($application);
            if(!$app){
                throw $this->createNotFoundException("La Ficha de Consulta que se pretende utilizar no existe.");
            }

            $inst = $institutionRepo->find($app->getInstitution());
            if(!$inst){
                throw $this->createNotFoundException("La Institución Extranjera con la que se pretende realizar el convenio no existe.");
            }

            $institutional->setApplication($app);
            $institutional->setInstitution($inst);
        }

        $form   = $this->createForm('DRI\AgreementBundle\Form\InstitutionalType', $institutional, [
            'currentAction' => 'new'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $app->setUsed(true);
            $institutional->setCountry($institutional->getInstitution()->getCountry());

            $em->persist($institutional);
            $em->flush();

            $editLink = $this->generateUrl('agreement_institutional_edit', array('id' => $institutional->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se creó un nuevo Convenio Institucional.</a>" );

            return $this->redirectToRoute('agreement_institutional_show', ['id' => $institutional->getId()]);
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('DRIAgreementBundle:Institutional:new.html.twig', array(
            'lastPage' => $lastVisited,
            'institution' => $inst,
            'application' => $app,
            'institutional' => $institutional,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Institutional entity.
     *
     * @param Institutional $institutional
     * @Route("/view/{id}", name="agreement_institutional_show", options = {"expose" = true}, methods={"GET"})
     * @return Response
     */
    public function showAction(Institutional $institutional)
    {
        $deleteForm = $this->createDeleteForm($institutional);
        return $this->render('DRIAgreementBundle:Institutional:show.html.twig', array(
            'institutional' => $institutional,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Institutional entity.
     *
     * @param Request $request
     * @param Institutional $institutional
     * @Route("/edit/{id}", name="agreement_institutional_edit", options = {"expose" = true}, methods={"GET", "POST"})
     * @Security("has_role('ROLE_MANAGE_SPECIALIST')")
     * @return Response
     */
    public function editAction(Request $request, Institutional $institutional)
    {
        if (!$institutional->isExpired()) {
            $user = null;

            if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $user = $this->getUser();  // get $user object
            }

            if ($user){
                $institutional->setLastUpdateBy($user);
            }

            $deleteForm = $this->createDeleteForm($institutional);
            $editForm = $this->createForm('DRI\AgreementBundle\Form\InstitutionalType', $institutional, [
                'currentAction' => 'edit'
            ])->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $institutional->setCountry($institutional->getInstitution()->getCountry());

                $em->persist($institutional);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'El Convenio Institucional fué editado satisfactoriamente!');
                return $this->redirectToRoute('agreement_institutional_show', array('id' => $institutional->getId()));
            }

            $lastVisited = $request->server->get('HTTP_REFERER');

            return $this->render('DRIAgreementBundle:Institutional:edit.html.twig', array(
                'lastPage' => $lastVisited,
                'institutional' => $institutional,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else{
            $this->get('session')->getFlashBag()->add(
                'error',
                'El Convenio Institucional expiró por lo que no se puede modificar.'
            );

            return $this->redirectToRoute('agreement_institutional_show', array('id' => $institutional->getId()));
        }
    }

    /**
     * Deletes a Institutional entity.
     *
     * @param Request $request
     * @param Institutional $institutional
     * @Route("/{id}", name="agreement_institutional_delete", methods={"DELETE"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     */
    public function deleteAction(Request $request, Institutional $institutional)
    {
    
        $form = $this->createDeleteForm($institutional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($institutional);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Institutional was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Institutional');
        }
        
        return $this->redirectToRoute('agreement_institutional_list');
    }
    
    /**
     * Creates a form to delete a Institutional entity.
     *
     * @param Institutional $institutional The Institutional entity
     * @return FormInterface The form
     */
    private function createDeleteForm(Institutional $institutional)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('agreement_institutional_delete', array('id' => $institutional->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Institutional by id
     *
     * @param Institutional $institutional The entity id
     * @Route("/delete/{id}", name="agreement_institutional_by_id_delete", methods={"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     */
    public function deleteByIdAction(Institutional $institutional){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($institutional);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'El Convenio Institucional fué borrado satisfatoriamente');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Existe un problema para borrar el Convenio Institucional');
        }

        return $this->redirect($this->generateUrl('agreement_institutional_list'));

    }

    /**
     * Bulk Action
     *
     * @param Request $request
     * @Route("/bulk-action/", name="agreement_institutional_bulk_delete", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
    */
    public function bulkAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $choices = $request->request->get('data');
            $token = $request->request->get('token');

            if (!$this->isCsrfTokenValid('multiselect', $token)) {
                throw new AccessDeniedException('El token CSRF no es valido.');
            }

            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('DRIAgreementBundle:Institutional');

            $counter = 0;

            foreach ($choices as $choice) {
                $entity = $repository->find($choice['id']);
                $em->remove($entity);
                $counter++;
            }

            $em->flush();

            return new Response('Se borraron satisfactoriamente '.$counter.' Convenios', 200);
        }


        return new Response('Solicitud incorrecta', 400);
    }

    /**
     * Returns a JSON string with the dependencies of the Application with the providen id.
     *
     * @param Request $request
     * @Route("/list_agreement_application_dependencies", name="list_agreement_application_dependencies", options={"expose"=true}, methods={"POST"})
     * @return JsonResponse|Response
     */
    public function listApplicationDependenciesAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();
        $em = $this->getDoctrine()->getManager();
        $applicationRepository = $em->getRepository("DRIAgreementBundle:Application");

        if ($isAjax) {
            $application = $request->request->get('application');
            $application = (int)$application;

            $app = $applicationRepository->findOneBy(['Id'],$application);

            if(!$app){
                throw $this->createNotFoundException("No llego la ficha.");
            }

            // Search the institutions that belongs to the application with the given id as GET parameter "application"
            $id     = $app->getInstitution()->getId();
            $name   = $app->getInstitution()->getName();

            // Serialize into an array the data that we need, in this case only number and id
            // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
            $institutionList = array();

            $institutionList[] = [
                "id" => $id,
                "name" => $name
            ];

            // Return array with structure of the passports of the providen client id
            return new JsonResponse(
                array(
                    'institutions' => $institutionList
                )
            );
        }

        return new JsonResponse([
            'success' => false,
            'message' => 'Error a la hora de obtener las dependencias',
        ]);
    }

    /**
     * Returns a JSON string with the dependencies of the Application with the providen id.
     *
     * @Route("/report/annual", name="list_agreement_annual_report", options={"expose"=true}, methods={"GET", "POST"})
     * @return Response
     */
    public function listAnnualReportAction()
    {
        $countryArray = $this->getAnnualReport();

        return $this->render('DRIAgreementBundle:Institutional:report.html.twig',
            array(
                'countryArray'    => $countryArray,
            )
        );
    }

    /**
     * Generate and save a PDF
     *
     * @Route("/report/annual/pdf", name="list_agreement_annual_report_pdf")
     */
    public function pdfAnnualReportAction() {
        $countryArray = $this->getAnnualReport();

        $header = $this->renderView(':pdf:header_pdf_base.html.twig');
        $footer = $this->renderView(':pdf:footer_pdf_base.html.twig');

        $html = $this->renderView('DRIAgreementBundle:Institutional:pdf_report.html.twig', [
            'countryArray' => $countryArray
        ]);

        $name = 'Reporte Anual';

        $filename = sprintf('%s-%s.pdf', $name, date('Y-m-d'));

        /*return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            [
                'images' => true,
                'enable-javascript' => true,
                'page-size' => 'A4',
                'viewport-size' => '1280x1024',
                'header-html' => $header,
                'footer-html' => $footer,
                'margin-left' => '10mm',
                'margin-right' => '10mm',
                'margin-top' => '30mm',
                'margin-bottom' => '25mm',
            ],
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );*/

        $response = new Response (
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html,
                [
                    'images' => true,
                    'enable-javascript' => true,
                    'header-html' => $header,
                    'footer-html' => $footer,
                    //'orientation' => 'landscape',
                ]),
            200,
            [
                'Content-Type'        => 'application/pdf',
                //'Content-Disposition' => sprintf('inline; filename="%s"', $filename),
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
        return $response;
    }

    /**
     * Generate and save a PDF
     *
     * @Route("/report/annual/word", name="list_agreement_annual_report_word")
     * @throws \Exception
     */
    public function annualReportAction()
    {
        $name = 'Reporte Anual de Convenios';
        $filename = sprintf('%s - %s.docx', $name, date('Y'));
        $template = "report_templates/agreements_report_template_1.docx";

        $currentPeriod = '2018 - 2019';
        $currentDate = sprintf('%s', date('d/m/Y'));

        $agreementsArray = array();
        $countriesArray = $this->getAnnualReport();

        foreach ($countriesArray as $item) {
            $agreementsArray[] = [
                'area'      => $item['region'],
                'country'   => $item['name'],
                'actives'   => $item['agreementsActive'],
                'reactives' => $item['agreementsReactived'],
                'signed'    => $item['agreementsSigned'],
                'total'     => $item['agreementsTotal'],
            ];
        }

        //var_dump($agreementsArray);
        // ask the service for a Word2007
        $templateObj = $this->get('phpword')->createTemplateObject($template);

        $templateObj->setValue('currentPeriod', $currentPeriod);
        $templateObj->setValue('currentDate', $currentDate);

        $templateObj->cloneRowAndSetValues('area', $agreementsArray);

        $templateOut = $templateObj->save();

        $response = new BinaryFileResponse($templateOut);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }

    /**
     * @return array
     */
    public function getAnnualReport(){
        $em = $this->getDoctrine()->getManager();

        $institutionalRepo  = $em->getRepository('DRIAgreementBundle:Institutional');
        $countryRepo        = $em->getRepository('DRIUsefulBundle:Country');

        $agreementList = $institutionalRepo->findAll();

        $countriesList = array();

        foreach ($agreementList as $agreement) {
            $countriesList[] = $agreement->getCountry();
        }

        $countriesList = array_unique($countriesList);

        $countryArray = array();

        foreach ($countriesList as $country) {
            $agreementsActive = 0;
            $agreementsReactived = 0;
            $agreementsSigned = 0;

            $currentCountry = $countryRepo->findOneBy($country);
            $continent = $currentCountry->getContinent();
            if($currentCountry->getSubArea() == null){
                $area = $currentCountry->getArea();
            }else{
                $area = $currentCountry->getSubArea();
            }

            $agreementsTotal = count($institutionalRepo->findBy(['country'],$country));

            foreach ($institutionalRepo->findBy(['country'],$country) as $agreement){
                if($agreement->isActive()){
                    $agreementsActive = $agreementsActive + 1;
                }
                if ($agreement->getActionType() == "FIR"){
                    $agreementsSigned = $agreementsSigned + 1;
                }elseif ($agreement->getActionType() == "REA"){
                    $agreementsReactived = $agreementsReactived + 1;
                }
            }

            $countryArray[] = array(
                'cont'                  => $continent,
                'region'                => $area,
                'name'                  => $currentCountry->getSpName(),
                'agreementsTotal'       => $agreementsTotal,
                'agreementsActive'      => $agreementsActive,
                'agreementsSigned'      => $agreementsSigned,
                'agreementsReactived'   => $agreementsReactived,
            );
        }

        foreach ($countryArray as $key => $row) {
            $cont[$key]   = $row['cont'];
            $region[$key] = $row['region'];
            $name[$key]   = $row['name'];
        }

        array_multisort($cont, SORT_ASC, $region, SORT_ASC, $name, SORT_ASC, $countryArray);

        return $countryArray;
    }
}