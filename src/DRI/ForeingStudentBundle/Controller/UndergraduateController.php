<?php

namespace DRI\ForeingStudentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Constraints\Date;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Elastica\Exception\NotFoundException;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use PhpParser\Node\Scalar\String_;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;
use Sg\DatatablesBundle\Datatable\DatatableInterface;

// Include the BinaryFileResponse and the ResponseHeaderBag
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

// Include the requires classes of Phpword
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\TemplateProcessor;

use DRI\ForeingStudentBundle\Entity\Undergraduate;
use DRI\ForeingStudentBundle\Form\UndergraduateType;
use DRI\ForeingStudentBundle\Datatables\UndergraduateDatatable;
use DRI\UsefulBundle\Useful\Useful;

/**
 * Undergraduate controller.
 *
 * @Route("/undergraduate")
 */
class UndergraduateController extends Controller
{
    /**
     * Lists all Undergraduate entities.
     *
     * @Route("/index", name="undergraduate_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('DRIForeingStudentBundle:Undergraduate')->createQueryBuilder('e');

        list($undergraduates, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('DRIForeingStudentBundle:Undergraduate:index.html.twig', array(
            'totalOfRecordsString' => $totalOfRecordsString,
            'undergraduates' => $undergraduates,
            'pagerHtml' => $pagerHtml,

        ));
    }


    /**
     * Lists all Undergraduate entities.
     * @param Request $request
     *
     * @Route("/list", name="undergraduate_list")
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
        $datatable = $this->get('sg_datatables.factory')->create(UndergraduateDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DRIForeingStudentBundle:Undergraduate:list.html.twig', array(
            'datatable' => $datatable,
        ));
    }


    /**
    * Get results from paginator and get paginator view.
    *
    */
    protected function paginator($queryBuilder, Request $request)
    {
        //sorting
        $sortCol = $queryBuilder->getRootAlias().'.'.$request->get('pcg_sort_col', 'id');
        $queryBuilder->orderBy($sortCol, $request->get('pcg_sort_order', 'desc'));
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($request->get('pcg_show' , 10));

        try {
            $pagerfanta->setCurrentPage($request->get('pcg_page', 1));
        } catch (\Pagerfanta\Exception\OutOfRangeCurrentPageException $ex) {
            $pagerfanta->setCurrentPage(1);
        }
        
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me, $request)
        {
            $requestParams = $request->query->all();
            $requestParams['pcg_page'] = $page;
            return $me->generateUrl('undergraduate_list', $requestParams);
        };

        // Paginator - view
        $view = new TwitterBootstrap3View();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => 'previous',
            'next_message' => 'next',
        ));

        return array($entities, $pagerHtml);
    }
    
    
    
    /*
     * Calculates the total of records string
     */
    protected function getTotalOfRecordsString($queryBuilder, $request) {
        $totalOfRecords = $queryBuilder->select('COUNT(e.id)')->getQuery()->getSingleScalarResult();
        $show = $request->get('pcg_show', 10);
        $page = $request->get('pcg_page', 1);

        $startRecord = ($show * ($page - 1)) + 1;
        $endRecord = $show * $page;

        if ($endRecord > $totalOfRecords) {
            $endRecord = $totalOfRecords;
        }
        return "Showing $startRecord - $endRecord of $totalOfRecords Records.";
    }
    
    

    /**
     * Displays a form to create a new Undergraduate entity.
     *
     * @Route("/new", name="undergraduate_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_FS_SPECIALIST')")
     */
    public function newAction(Request $request)
    {
        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $undergraduate = new Undergraduate();

        if ($user){
            $undergraduate->setCreatedBy($user);
        }

        $form   = $this->createForm('DRI\ForeingStudentBundle\Form\UndergraduateType', $undergraduate, [
            'currentAction' => 'new'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($undergraduate);
            $em->flush();
            
            $editLink = $this->generateUrl('undergraduate_edit', array('fullNameSlug' => $undergraduate->getFullNameSlug()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se ha creado un nuevo estudiante de pregrado.</a>" );

            return $this->redirectToRoute('undergraduate_show', array('fullNameSlug' => $undergraduate->getFullNameSlug()));
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('DRIForeingStudentBundle:Undergraduate:new.html.twig', array(
            'lastPage' => $lastVisited,
            'undergraduate' => $undergraduate,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Undergraduate entity.
     *
     * @Route("/profile/{fullNameSlug}", name="undergraduate_show", options = {"expose" = true})
     * @Method("GET")
     */
    public function showAction(Undergraduate $undergraduate)
    {
        $deleteForm = $this->createDeleteForm($undergraduate);
        return $this->render('DRIForeingStudentBundle:Undergraduate:show.html.twig', array(
            'undergraduate' => $undergraduate,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Undergraduate entity.
     *
     * @Route("/edit/{fullNameSlug}", name="undergraduate_edit", options = {"expose" = true})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_FS_SPECIALIST')")
     */
    public function editAction(Request $request, Undergraduate $undergraduate)
    {
        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        if ($user){
            $undergraduate->setLastUpdateBy($user);
        }

        $deleteForm = $this->createDeleteForm($undergraduate);
        $editForm = $this->createForm('DRI\ForeingStudentBundle\Form\UndergraduateType', $undergraduate, [
            'currentAction' => 'edit'
        ])->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($undergraduate);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'El Estudiante de Pregrado se editó satisfactoriamente!');
            return $this->redirectToRoute('undergraduate_edit', array('fullNameSlug' => $undergraduate->getFullNameSlug()));
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('DRIForeingStudentBundle:Undergraduate:edit.html.twig', array(
            'lastPage' => $lastVisited,
            'undergraduate' => $undergraduate,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Undergraduate entity.
     *
     * @Route("/{id}", name="undergraduate_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Undergraduate $undergraduate)
    {
    
        $form = $this->createDeleteForm($undergraduate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($undergraduate);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Undergraduate was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Undergraduate');
        }
        
        return $this->redirectToRoute('undergraduate_list');
    }
    
    /**
     * Creates a form to delete a Undergraduate entity.
     *
     * @param Undergraduate $undergraduate The Undergraduate entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Undergraduate $undergraduate)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('undergraduate_delete', array('id' => $undergraduate->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Undergraduate by id
     *
     * @Route("/delete/{id}", name="undergraduate_by_id_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteByIdAction(Undergraduate $undergraduate){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($undergraduate);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Undergraduate was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Undergraduate');
        }

        return $this->redirect($this->generateUrl('undergraduate_list'));

    }
    

    /**
    * Bulk Action
    * @Route("/bulk-action/", name="undergraduate_bulk_delete")
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
            $repository = $em->getRepository('DRIForeingStudentBundle:Undergraduate');

            $counter = 0;

            foreach ($choices as $choice) {
                $entity = $repository->find($choice['id']);
                $em->remove($entity);
                $counter++;
            }

            $em->flush();

            return new Response('Se borraron satisfactoriamente '.$counter.' estudiantes', 200);
        }


        return new Response('Solicitud incorrecta', 400);
    }


    /**
     * Available CI.
     *
     * @param Request $request
     *
     * @Route("/ci-available", name="undergraduate_ci_is_available", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @return JsonResponse|Response
     */
    public function isAvailableCIAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $ci = $request->request->get('ci');

            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository('DRIForeingStudentBundle:Undergraduate')->findOneByCi($ci);

            if(!$exist){
                return new JsonResponse(true);
            }
            else {
                return new JsonResponse(false);
            }
        }

    }

    /**
     * Available Email.
     *
     * @param Request $request
     *
     * @Route("/email-available", name="undergraduate_email_is_available", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @return JsonResponse|Response
     */
    public function isAvailableEmailAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $email = $request->request->get('email');

            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository('DRIForeingStudentBundle:Undergraduate')->findOneByEmail($email);

            if(!$exist){
                return new JsonResponse(true);
            }
            else {
                return new JsonResponse(false);
            }
        }

    }

    /**
     * @Route("/word", name="undergraduate_word")
     */
    public function wordAction(Request $request)
    {

        $phpWord = new PhpWord();

        // Define styles
        $phpWord->addTitleStyle(1, array('size' => 14, 'bold' => true), array('keepNext' => true, 'spaceBefore' => 240));
        $phpWord->addTitleStyle(2, array('size' => 14, 'bold' => true), array('keepNext' => true, 'spaceBefore' => 240));


        // 2D charts
        $section = $phpWord->addSection();
        $section->addTitle('2D charts', 1);
        $section = $phpWord->addSection(array('colsNum' => 2, 'breakType' => 'continuous'));
        $chartTypes = array('pie', 'doughnut', 'bar', 'column', 'line', 'area', 'scatter', 'radar');
        $twoSeries = array('bar', 'column', 'line', 'area', 'scatter', 'radar');
        $threeSeries = array('bar', 'line');
        $categories = array('A', 'B', 'C', 'D', 'E');
        $series1 = array(1, 3, 2, 5, 4);
        $series2 = array(3, 1, 7, 2, 6);
        $series3 = array(8, 3, 2, 5, 4);

        foreach ($chartTypes as $chartType) {
            $section->addTitle(ucfirst($chartType), 2);
            $chart = $section->addChart($chartType, $categories, $series1);
            $chart->getStyle()->setWidth(Converter::inchToEmu(2.5))->setHeight(Converter::inchToEmu(2));
            if (in_array($chartType, $twoSeries)) {
                $chart->addSeries($categories, $series2);
            }
            if (in_array($chartType, $threeSeries)) {
                $chart->addSeries($categories, $series3);
            }
            $section->addTextBreak();
        }

        // 3D charts
        $section = $phpWord->addSection(array('breakType' => 'continuous'));
        $section->addTitle('3D charts', 1);
        $section = $phpWord->addSection(array('colsNum' => 2, 'breakType' => 'continuous'));
        $chartTypes = array('pie', 'bar', 'column', 'line', 'area');
        $multiSeries = array('bar', 'column', 'line', 'area');
        $style = array('width' => Converter::cmToEmu(5), 'height' => Converter::cmToEmu(4), '3d' => true);

        foreach ($chartTypes as $chartType) {

            $section->addTitle(ucfirst($chartType), 2);

            $chart = $section->addChart($chartType, $categories, $series1, $style);

            if (in_array($chartType, $multiSeries)) {
                $chart->addSeries($categories, $series2);
                $chart->addSeries($categories, $series3);
            }

            $section->addTextBreak();
        }


        // Saving the document as OOXML file...
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

        $filePath = 'hello_world_charts.docx';
        // Write file into path
        $objWriter->save($filePath);

        return new Response("File succesfully written at $filePath");
    }

    /**
     * @Route("/word-from-twig", name="undergraduate_word_from_twig")
     */
    public function wordFromTwigAction(){

        $pregrado = $this->getDoctrine()
            ->getRepository(Undergraduate::class)
            ->findAll();

        $myHtml = $this->renderView('DRIForeingStudentBundle:Undergraduate:word_report.html.twig', array(
            'pregrado' => $pregrado,
        ));
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        Html::addHtml($section, $myHtml, true);
        // Saving the document as OOXML file...

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $filePath = 'hello_world_charts.docx';
        // Write file into path
        $objWriter->save($filePath);

        return new Response("File succesfully written at $filePath");
    }

    /**
     * @Route("/word-export", name="word_export")
     */
    public function wordExportAction(Request $request)
    {
        // Create a new Word document
        $phpWord = new PhpWord();

        /* Note: any element you append to a document must reside inside of a Section. */

        // Adding an empty Section to the document...
        $section = $phpWord->addSection();
        // Adding Text element to the Section having font styled by default...
        $section->addText(
            '"Learn from yesterday, live for today, hope for tomorrow. '
            . 'The important thing is not to stop questioning." '
            . '(Albert Einstein)'
        );

        // Saving the document as OOXML file...
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

        // Create a temporal file in the system
        $fileName = 'hello_world_download_file.docx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Write in the temporal filepath
        $objWriter->save($temp_file);

        // Send the temporal file as response (as an attachment)
        $response = new BinaryFileResponse($temp_file);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        );

        return $response;
    }

    /**
     * @Route("/word-tmpl", name="word_tmpl")
     */
    public function wordTmplAction(Request $request)
    {
        // Create a temporal file in the system
        $fileName = 'result.docx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $templateProcessor = new TemplateProcessor('prueba.docx');

        $templateProcessor->cloneRow('pais', 5);

        $templateProcessor->setValue('pais#1', 'España');
        $templateProcessor->setValue('pais#2', 'Inglaterra');
        $templateProcessor->setValue('pais#3', 'Italia');
        $templateProcessor->setValue('pais#4', 'Francia');
        $templateProcessor->setValue('pais#5', 'Holanda');

        $templateProcessor->setValue('nombre#1', 'Messi');
        $templateProcessor->setValue('nombre#2', 'Rashford');
        $templateProcessor->setValue('nombre#3', 'Cristiano');
        $templateProcessor->setValue('nombre#4', 'Mbape');
        $templateProcessor->setValue('nombre#5', 'De Jhon');

        $template = $templateProcessor->save();

        $phpWord = IOFactory::load($template);
        unlink($template);

        $xmlWriter = IOFactory::createWriter($phpWord, 'ODText');
        $xmlWriter->save($temp_file);

        // Write in the temporal filepath
        //  $objWriter->save();

        // Send the temporal file as response (as an attachment)
        $response = new BinaryFileResponse($temp_file);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        );

        return $response;
    }

    /**
     * @Route("/word-tmpl1", name="word_tmpl1")
     */
    public function wordTmpl1Action(Request $request)
    {
        $pregrado = $this->getDoctrine()
            ->getRepository(Undergraduate::class)
            ->findOneById(1);

        $fileName = "prueba.docx";

        // ask the service for a Word2007
        $phpTemplateObject = $this->get('phpword')->createTemplateObject($fileName);
        $phpTemplateObject->setValue('pais', $pregrado->getCountry());
        $phpTemplateObject->setValue('nombre', $pregrado->getFullName());

        $phpWordObject = $this->get('phpword')->getPhpWordObjFromTemplate($phpTemplateObject);

        // create the writer
        $writer = $this->get('phpword')->createWriter($phpWordObject, 'Word2007');
        // create the response
        $response = $this->get('phpword')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'result.docx'
        );
        $response->headers->set('Content-Type', 'application/msword');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;

    }

    /**
     * @Route("/annual-report", name="annual_report")
     */
    public function annualReportAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $name = 'Reporte Anual de Pregrado';
        $filename = sprintf('%s - %s.docx', $name, date('Y'));
        //$temp_file = tempnam(sys_get_temp_dir(), $filename);
        $template = "report_templates/undergradutate_report_template_2.docx";
        $currentDate = sprintf('%s', date('d/m/Y'));
        $fullTotal      = 0;
        $femaleTotal    = 0;
        $maleTotal      = 0;
        $fcoTotal       = 0;
        $becTotal       = 0;
        $autTotal       = 0;
        $countriesIncidence   = array();
        $fcoCountriesIncidence   = array();
        $becCountriesIncidence   = array();
        $autCountriesIncidence   = array();

        $fcoSumary   = '';
        $becSumary   = '';
        $autSumary   = '';

        $fcoList   = array();
        $becList   = array();
        $autList   = array();

        $undergraduateRepo = $em->getRepository(Undergraduate::class);
        $contryRepo = $em->getRepository(Undergraduate::class);

        $undergraduates = $undergraduateRepo->findAll();
        $fco = $undergraduateRepo->findByType('FCO');
        $bec = $undergraduateRepo->findByType('BEC');
        $aut = $undergraduateRepo->findByType('AUT');

        if($undergraduates){
            $fullTotal      = count($undergraduates);
            $femaleTotal    = count($undergraduateRepo->findByGender('F'));
            $maleTotal      = count($undergraduateRepo->findByGender('M'));

            $countriesIncidence = $this->incidencePerCountries($undergraduates, null);

            if($fco){
                $fcoTotal = count($fco);
                $fcoCountriesIncidence = $this->incidencePerCountries($fco, 'FCO');
                $fcoSumary = $this->genCountrySumaryPerType($fcoCountriesIncidence);
                $fcoList = $this->genListPerType($fco, 'fco');
            }

            if($bec){
                $becTotal = count($bec);
                $becCountriesIncidence = $this->incidencePerCountries($bec, 'BEC');
                $becSumary = $this->genCountrySumaryPerType($becCountriesIncidence);
                $becList = $this->genListPerType($bec, 'bec');
            }

            if($aut){
                $autTotal = count($aut);
                $autCountriesIncidence = $this->incidencePerCountries($aut, 'AUT');
                $autSumary = $this->genCountrySumaryPerType($autCountriesIncidence);
                $autList = $this->genListPerType($aut, 'aut');
            }

        }

        // ask the service for a Word2007
        $templateObj = $this->get('phpword')->createTemplateObject($template);
        $templateObj->setValue('currentPeriod', '2018 - 2019');
        $templateObj->setValue('currentDate', $currentDate);
        $templateObj->setValue('fullTotal', $fullTotal);
        $templateObj->setValue('femaleTotal', $femaleTotal);
        $templateObj->setValue('maleTotal', $maleTotal);
        $templateObj->setValue('fcoTotal', $fcoTotal);
        $templateObj->setValue('becTotal', $becTotal);
        $templateObj->setValue('autTotal', $autTotal);
        $templateObj->setValue('fcoList', $fcoSumary);
        $templateObj->setValue('becList', $becSumary);
        $templateObj->setValue('autList', $autSumary);
        $templateObj->cloneRowAndSetValues('country', $countriesIncidence);
        if ($fco)
            $templateObj->cloneRowAndSetValues('fcoNo', $fcoList);
        if ($bec)
            $templateObj->cloneRowAndSetValues('becNo', $becList);
        if ($aut)
            $templateObj->cloneRowAndSetValues('autNo', $autList);

        $templateOut = $templateObj->save();

        $response = new BinaryFileResponse($templateOut);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;

        /*$wordObj = $this->get('phpword')->getPhpWordObjFromTemplate($templateObj);

        // create the writer
        $writer = $this->get('phpword')->createWriter($wordObj, 'Word2007');
        // create the response
        $response = $this->get('phpword')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );
        $response->headers->set('Content-Type', 'application/msword');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;*/
    }

    public function incidencePerCountries($elements, $type){

        $em = $this->getDoctrine()->getManager();
        $underRepo = $em->getRepository(Undergraduate::class);

        $cList      = [];
        $cIncidence = [];

        foreach ($elements as $element) {
            $cList[] = $element->getCountry();
        }

        $cList = array_unique($cList);
        $fTotal     = 0;
        $femaleCT   = 0;
        $maleCT     = 0;

        foreach ($cList as $c) {
            $fTotal     = count($underRepo->findCountryAndType($c, $type));
            $femaleCT   = count($underRepo->findCountryAndGenderAndType($c, 'F', $type));
            $maleCT     = count($underRepo->findCountryAndGenderAndType($c, 'M', $type));

            $cIncidence[] = array(
                'country'       => $c->getSpName(),
                'countryCount'  => $fTotal,
                'female'        => $femaleCT,
                'male'          => $maleCT,
            );
        }

        return $cIncidence;
    }

    public function genListPerType($undergraduates, $type){
        $em = $this->getDoctrine()->getManager();
        $underRepo = $em->getRepository(Undergraduate::class);

        $underList = array();
        $count = 0;

        foreach ($undergraduates as $undergraduate) {
            $count = $count + 1;

            $underList[] = array(
                $type.'No'        => $count,
                $type.'FirstName' => $undergraduate->getNames(),
                $type.'LastName'  => $undergraduate->getLastNames(),
                $type.'Gender'    => $undergraduate->getGender(),
                $type.'Ci'        => $undergraduate->getCi(),
                $type.'Passport'  => $undergraduate->getPassportNumber(),
                $type.'Country'   => $undergraduate->getCountry()->getSpName(),
                $type.'Career'    => $undergraduate->getCareer()->getName(),
                $type.'ExpiryDate'=> $undergraduate->getExpiryDate()->format('d/m/Y'),
                $type.'Year'      => $undergraduate->getYear(),
            );
        }

        return $underList;
    }

    public function genCountrySumaryPerType($elements){
        $sumary = '';

        foreach ($elements as $element){
            $sumary .= $element['country'].': '.$element['countryCount'].' ';
        }

        return $sumary;
    }

}
