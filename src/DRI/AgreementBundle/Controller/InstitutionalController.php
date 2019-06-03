<?php

namespace DRI\AgreementBundle\Controller;


use DRI\AgreementBundle\Datatables\InstitutionalDatatable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use DRI\PassportBundle\Entity\Application;
use DRI\PassportBundle\Datatables\ApplicationDatatable;
use DRI\UsefulBundle\Useful\Useful;

use PhpParser\Node\Scalar\String_;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

// Include the BinaryFileResponse and the ResponseHeaderBag
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

// Include the requires classes of Phpword
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\TemplateProcessor;

use DRI\AgreementBundle\Entity\Institutional;
use DRI\AgreementBundle\Form\InstitutionalType;


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
     * @Route("/index", name="agreement_institutional_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        return $this->render('DRIAgreementBundle:Institutional:index.html.twig', array(

        ));
    }

    /**
     * Lists all Institutional Agreements entities.
     *
     * @param Request $request
     *
     * @Route("/list", name="agreement_institutional_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.client');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(InstitutionalDatatable::class);
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
     * @Route("/new/{application}", name="agreement_institutional_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MANAGE_SPECIALIST')")
     */
    public function newAction(Request $request, $application = null)
    {
        $user   = null;
        $app    = null;
        $inst   = null;
        $lastVisited = $request->server->get('HTTP_REFERER');

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
     * @Route("/view/{id}", name="agreement_institutional_show", options = {"expose" = true})
     * @Method("GET")
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
     * @Route("/edit/{id}", name="agreement_institutional_edit", options = {"expose" = true})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MANAGE_SPECIALIST')")
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
     * @Route("/{id}", name="agreement_institutional_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
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
     *
     * @return \Symfony\Component\Form\Form The form
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
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="agreement_institutional_by_id_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
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
    * @Route("/bulk-action/", name="agreement_institutional_bulk_delete")
    * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
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
     * Assign numberSlug for Application entity.
     *
     * @Route("/assign_institutional_number/")
     * @Security("has_role('ROLE_ADMIN')")
     *
     */
    public function assignInstitutionalNumberSlug(){

        $em = $this->getDoctrine()->getManager();
        $instRepo   = $em->getRepository('DRIAgreementBundle:Institutional');

        $agreements = $instRepo->findByNumber('');

        foreach ($agreements as $agreement){
            $agreement->setNumber();

            $em->persist($agreement);
            $em->flush();
        }
    }

    /**
     * Returns a JSON string with the dependencies of the Application with the providen id.
     *
     * @param Request $request
     *
     * @Route("/list_agreement_application_dependencies", name="list_agreement_application_dependencies", options={"expose"=true})
     * @Method({"POST"})
     *
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



            $app = $applicationRepository->findOneById($application);

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

            //throw new Exception($institutionList);

            // Return array with structure of the passports of the providen client id
            return new JsonResponse(
                array(
                    'institutions'     => $institutionList
                )
            );
        }
    }

    /**
     * Returns a JSON string with the dependencies of the Application with the providen id.
     *
     * @param Request $request
     *
     * @Route("/report/annual", name="list_agreement_annual_report", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function listAnnualReportAction(Request $request)
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
    public function pdfAnnualReportAction(Request $request) {
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
     */
    public function annualReportAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

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


    public function getAnnualReport(){
        $em = $this->getDoctrine()->getManager();

        $institutionalRepo  = $em->getRepository('DRIAgreementBundle:Institutional');
        $institutionRepo    = $em->getRepository('DRIAgreementBundle:Institution');
        $countryRepo        = $em->getRepository('DRIUsefulBundle:Country');

        //for agreements pages

        $agreementList = $institutionalRepo->findAll();

        $countriesList = array();
        $finalArray = array();
        $regionsArray = array();

        foreach ($agreementList as $agreement) {
            //$regionsArray[] = $agreement->getInstitution()->getCountry()->getContinent();
            $countriesList[] = $agreement->getCountry();
        }

        //$regionsArray = array_unique($regionsArray);
        $countriesList = array_unique($countriesList);
        //asort($countriesArray);

        $countryArray = array();

        foreach ($countriesList as $country) {
            $agreementsTotal = 0;
            $agreementsActive = 0;
            $agreementsReactived = 0;
            $agreementsSigned = 0;

            $continent = $country->getContinent();
            if($country->getSubArea() == null){
                $area = $country->getArea();
            }else{
                $area = $country->getSubArea();
            }

            $agreementsTotal = count($institutionalRepo->findByCountry($country));

            foreach ($institutionalRepo->findByCountry($country) as $agreement){
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
                'name'                  => $country->getSpName(),
                'agreementsTotal'       => $agreementsTotal,
                'agreementsActive'      => $agreementsActive,
                'agreementsSigned'      => $agreementsSigned,
                'agreementsReactived'   => $agreementsReactived,
            );
        }

        foreach ($countryArray as $key => $row) {
            $cont[$key]   = $row['cont'];
            $region[$key]   = $row['region'];
            $name[$key]     = $row['name'];
        }

        array_multisort($cont, SORT_ASC, $region, SORT_ASC, $name, SORT_ASC, $countryArray);

        return $countryArray;
    }
}