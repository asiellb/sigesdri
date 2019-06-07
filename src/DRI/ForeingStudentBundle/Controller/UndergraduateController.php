<?php

namespace DRI\ForeingStudentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Doctrine\Common\Collections\ArrayCollection;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;

use Exception;

use DRI\ForeingStudentBundle\Entity\Undergraduate;

/**
 * Undergraduate controller.
 *
 * @Route("/undergraduate")
 */
class UndergraduateController extends Controller
{

    /**
     * Lists all Postgraduate entities.
     *
     * @Route("/", name="undergraduate_index", methods={"GET"})
     * @return Response
     */
    public function indexAction()
    {
        return new Response('Sin Implementar');
    }

    /**
     * Lists all Undergraduate entities.
     *
     * @param Request $request
     * @Route("/list", name="undergraduate_list", methods={"GET"})
     * @return Response
     * @throws Exception
     */
    public function listAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('app.datatable.foreingstudents.undergraduate');
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
     * Displays a form to create a new Undergraduate entity.
     *
     * @param Request $request
     * @Route("/new", name="undergraduate_new", methods={"GET", "POST"})
     * @Security("has_role('ROLE_FS_SPECIALIST')")
     * @return Response
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
     * @param Undergraduate $undergraduate
     * @Route("/profile/{fullNameSlug}", name="undergraduate_show", options = {"expose" = true}, methods={"GET"})
     * @return Response
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
     * @param Request $request
     * @param Undergraduate $undergraduate
     * @Route("/edit/{fullNameSlug}", name="undergraduate_edit", options = {"expose" = true}, methods={"GET", "POST"})
     * @Security("has_role('ROLE_FS_SPECIALIST')")
     * @return Response
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
     * @param Request $request
     * @param Undergraduate $undergraduate
     * @Route("/{id}", name="undergraduate_delete", methods={"DELETE"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
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
     * @return FormInterface The form
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
     * @param Undergraduate $undergraduate
     * @Route("/delete/{id}", name="undergraduate_by_id_delete", methods={"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
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
     *
     * @param Request $request
     * @Route("/bulk-action/", name="undergraduate_bulk_delete", methods={"POST"})
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
     * @Route("/ci-available", name="undergraduate_ci_is_available", options={"expose"=true}, methods={"GET", "POST"})
     * @return JsonResponse|Response
     */
    public function isAvailableCIAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $ci = $request->request->get('ci');

            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository('DRIForeingStudentBundle:Undergraduate')->findOneBy(['ci'],$ci);

            if(!$exist){
                return new JsonResponse(true);
            }
            else {
                return new JsonResponse(false);
            }
        }

        return new Response('Peticion Indevida', Response::HTTP_BAD_REQUEST);

    }

    /**
     * Available Email.
     *
     * @param Request $request
     * @Route("/email-available", name="undergraduate_email_is_available", options={"expose"=true}, methods={"GET", "POST"})
     * @return JsonResponse|Response
     */
    public function isAvailableEmailAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $email = $request->request->get('email');

            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository('DRIForeingStudentBundle:Undergraduate')->findOneBy(['email'],$email);

            if(!$exist){
                return new JsonResponse(true);
            }
            else {
                return new JsonResponse(false);
            }
        }

        return new Response('Peticion Indevida', Response::HTTP_BAD_REQUEST);

    }

    /**
     * @Route("/word", name="undergraduate_word")
     * @return Response
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function wordAction()
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
     * @return Response
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function wordFromTwigAction(){
        $em = $this->getDoctrine()->getManager();
        $undergraduates = $em->getRepository('DRIForeingStudentBundle:Undergraduate')->findAll();

        $myHtml = $this->renderView('DRIForeingStudentBundle:Undergraduate:word_report.html.twig', array(
            'undergraduates' => $undergraduates,
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
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function wordExportAction()
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
     * @return BinaryFileResponse
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     * @throws Exception
     */
    public function wordTmplAction()
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
     * @return StreamedResponse
     */
    public function wordTmpl1Action()
    {
        $pregrado = $this->getDoctrine()->getManager()
            ->getRepository('DRIForeingStudentBundle:Undergraduate')
            ->findOneBy(['id'],1);

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
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function annualReportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $underRepo = $em->getRepository('DRIForeingStudentBundle:Undergraduate');
        $undergraduates = $underRepo->findAll();

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

        $fcos = new ArrayCollection();
        $becs = new ArrayCollection();
        $auts = new ArrayCollection();
        $females = new ArrayCollection();
        $males = new ArrayCollection();

        foreach ($undergraduates as $undergraduate){
            switch ($undergraduate->getType()){
                case 'FCO': $fcos->add($undergraduate);break;
                case 'BEC': $becs->add($undergraduate);break;
                case 'AUT': $auts->add($undergraduate);break;
                default:break;
            }

            switch ($undergraduate->getGender()){
                case 'F': $females->add($undergraduate);break;
                case 'M': $males->add($undergraduate);break;
                default:break;
            }

        }

        $fcoSumary   = '';
        $becSumary   = '';
        $autSumary   = '';

        $countriesIncidence   = array();
        $fcoList   = array();
        $becList   = array();
        $autList   = array();

        if($undergraduates){
            $fullTotal      = count($undergraduates);
            $femaleTotal    = $females->count();
            $maleTotal      = $males->count();

            $countriesIncidence = $this->incidencePerCountries($undergraduates, null);

            if($fcos->count() > 0){
                $fcoTotal = $fcos->count();
                $fcoCountriesIncidence = $this->incidencePerCountries($fcos, 'FCO');
                $fcoSumary = $this->genCountrySumaryPerType($fcoCountriesIncidence);
                $fcoList = $this->genListPerType($fcos, 'fco');
            }

            if($becs->count() > 0){
                $becTotal = $becs->count();
                $becCountriesIncidence = $this->incidencePerCountries($becs, 'BEC');
                $becSumary = $this->genCountrySumaryPerType($becCountriesIncidence);
                $becList = $this->genListPerType($becs, 'bec');
            }

            if($auts->count() > 0){
                $autTotal = $auts->count();
                $autCountriesIncidence = $this->incidencePerCountries($auts, 'AUT');
                $autSumary = $this->genCountrySumaryPerType($autCountriesIncidence);
                $autList = $this->genListPerType($auts, 'aut');
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
        if ($fcos->count() > 0)
            $templateObj->cloneRowAndSetValues('fcoNo', $fcoList);
        if ($becs->count() > 0)
            $templateObj->cloneRowAndSetValues('becNo', $becList);
        if ($auts->count() > 0)
            $templateObj->cloneRowAndSetValues('autNo', $autList);

        $templateOut = $templateObj->save();

        $response = new BinaryFileResponse($templateOut);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }

    /**
     * @param ArrayCollection|array $elements
     * @param string $type
     * @return array
     */
    public function incidencePerCountries($elements, $type){

        $em = $this->getDoctrine()->getManager();
        $underRepo = $em->getRepository('DRIForeingStudentBundle:Undergraduate');

        $cList      = new ArrayCollection();
        $cIncidence = array();


        foreach ($elements as $element) {
            if (!$cList->contains($element))
                $cList->add($element->getCountry());
        }

        if ($cList->count() > 0) {
            foreach ($cList as $c) {
                $fTotal = count($underRepo->findCountryAndType($c, $type));
                $femaleCT = count($underRepo->findCountryAndGenderAndType($c, 'F', $type));
                $maleCT = count($underRepo->findCountryAndGenderAndType($c, 'M', $type));

                $cIncidence[] = array(
                    'country' => $c->getSpName(),
                    'countryCount' => $fTotal,
                    'female' => $femaleCT,
                    'male' => $maleCT,
                );
            }
        }else{
            $cIncidence = array(
                'country' => '',
                'countryCount' => '',
                'female' => '',
                'male' => '',
            );
        }

        return $cIncidence;
    }

    /**
     * @param ArrayCollection $undergraduates
     * @param string $type
     * @return array
     */
    public function genListPerType($undergraduates, $type){
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

    /**
     * @param array $elements
     * @return string
     */
    public function genCountrySumaryPerType($elements){
        $sumary = '';

        foreach ($elements as $element){
            $sumary .= $element['country'].': '.$element['countryCount'].' ';
        }

        return $sumary;
    }

}
