<?php

namespace DRI\ExitBundle\Controller;

use DRI\ExitBundle\Entity\Economic;
use Elastica\Exception\NotFoundException;
use function Matrix\add;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
    PhpOffice\Common\XMLWriter
    ;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOF;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\Reader;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use HTMLtoOpenXML\Parser;

use DRI\ExitBundle\Entity\Application,
    DRI\ExitBundle\Form\ApplicationType,
    DRI\ExitBundle\Datatables\ApplicationDatatable,
    DRI\UsefulBundle\Useful\Useful;

use DOMDocument;

/**
 * Exit Report controller.
 *
 * @Route("/report")
 */
class ReportController extends Controller
{

    /**
     * Generate Event Acount Report
     *
     * @param Request $request
     * @param integer $year
     *
     * @Route("/event-acount/{year}", name="exit_report_event_acount")
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function reportEventAcount(Request $request, $year = null){
        $em = $this->getDoctrine()->getManager();
        $appRepo = $em->getRepository('DRIExitBundle:Application');

        $apps = $appRepo->getAppsCreatedAtWithEvenAcount();
        $years = array();

        foreach ($apps as $app){
            $years[date_format($app['createdAt'], 'Y')] = date_format($app['createdAt'], 'Y');
        }

        if(!$year){
            $year = date_format(date_create(), 'Y');
        }

        $year_form = $this->createFormBuilder()
            ->add('year', ChoiceType::class, array(
                'label' => false,
                'placeholder' => 'Seleccione el Año',
                'choices'  => $years,
                'attr' => array(
                    'class'=>'bs-select',
                ),
            ))
            ->getForm();

        $year_form->handleRequest($request);

        if ($year_form->isSubmitted() && $year_form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $year_form->getData();
            $year = $data['year'];
        }

        $result = $this->getAppsForYearWithEvenAcountList($year);
        $entries = $result['entries'];

        for ($key = 0, $size = count($entries); $key < $size; $key++) {
            $entries[$key]['concepts'] = join('<br>', $entries[$key]['concepts']);
            $entries[$key]['objetives'] = join('<br>', $entries[$key]['objetives']);
            $entries[$key]['countries'] = join('<br>', $entries[$key]['countries']);
        }

        $totalImport = $result['totalImport'];
        $availability = $result['availability'];

        return $this->render('DRIExitBundle:Reports:event_acount.html.twig', array(
            'year' => $year,
            'year_form' => $year_form->createView(),
            'entries' => $entries,
            'totalImport' => $totalImport,
            'availability' => $availability,
        ));
    }

    /**
     * Generate and save a Event Acount Report to Word
     *
     * @param integer $year
     *
     * @Route("/event-acount-word/{year}", name="exit_report_event_acount_to_word")
     *
     * @return BinaryFileResponse
     */
    public function reportEventAcountToWord($year = null)
    {
        $em = $this->getDoctrine()->getManager();

        $currentDate = sprintf('%s', date('d/m/Y'));
        $name = 'Reporte de Cuenta Eventualidades';
        $template = "report_templates/event_acount_template.docx";

        if ($year){
            $filename = sprintf('%s - %s.docx', $name, $year);
        }else{
            $year = sprintf('%s', date('Y'));
            $filename = sprintf('%s - %s.docx', $name, $year);
        }

        $initial = $this->getAppsForYearWithEvenAcountList($year);

        $entries = $initial['entries'];
        $totalImport = $initial['totalImport'];
        $availability = $initial['availability'];


        for ($key = 0, $size = count($entries); $key < $size; $key++) {
            $entries[$key]['concepts'] = join('</w:t><w:br/><w:t>', $entries[$key]['concepts']);
            $entries[$key]['objetives'] = join('</w:t><w:br/><w:t>', $entries[$key]['objetives']);
            $entries[$key]['countries'] = join('</w:t><w:br/><w:t>', $entries[$key]['countries']);
        }

        //var_dump($agreementsArray);
        // ask the service for a Word2007
        $templateObj = $this->get('phpword')->createTemplateObject($template);

        $templateObj->setValue('year', $year);
        $templateObj->setValue('currentDate', $currentDate);

        $templateObj->cloneRowAndSetValues('client', $entries);

        $templateObj->setValue('totalImport', $totalImport);
        $templateObj->setValue('availability', $availability);

        $templateOut = $templateObj->save();

        $response = new BinaryFileResponse($templateOut);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }

    public function getAppsForYearWithEvenAcountList($year){
        $em = $this->getDoctrine()->getManager();
        $appRepo = $em->getRepository('DRIExitBundle:Application');

        $yearEntries = $appRepo->getAppsForYearWithEvenAcount($year);

        $entries = array();
        $totalImport = 0;

        foreach ($yearEntries as $entry){
            $client     = $entry->getClient()->getFullName();
            $ci         = $entry->getClient()->getCi();
            $area       = $entry->getClient()->getFaculty() != null ? $entry->getClient()->getFaculty()->getName() : $entry->getClient()->getWorkersWorkPlace();
            switch ($entry->getClient()->getClientType()){
                case 'dir':$category   = 'Directivo';break;
                case 'doc':$category   = 'Docente';break;
                case 'nod':$category   = 'No Docente';break;
                case 'est':$category   = 'Estudiante';break;
                default:break;
            }
            $concepts    = array();
            $objetives   = array();
            $countries    = array();
            $exitDate   = $entry->getExitDate()->format('d/m/Y');
            $lapsed     = $entry->getLapsed();
            $import     = 0.00;

            $count = 0;
            foreach ($entry->getMissions() as $mission){
                $count = $count+1;
                if ($count == 1){
                    $objetives[] = $mission->getObjetives();
                    $countries[]  = $mission->getCountry()->getSpName();
                }else{
                    $objetives[] = $mission->getObjetives();
                    $countries[]  = $mission->getCountry()->getSpName();
                }
                $economics = $mission->getEconomics();

                foreach ($economics as $economic){
                    if ($economic->getEventAcount() == true){
                        switch ($economic->getType()){
                            case 'P' :$concepts[]  = 'Pasaje';break;
                            case 'PA':$concepts[]  = 'Pasaje Aéreo';break;
                            case 'E' :$concepts[]  = 'Estancia';break;
                            case 'SM':$concepts[]  = 'Seguro Médico';break;
                            case 'V' :$concepts[]  = 'Visa';break;
                            case 'D' :$concepts[]  = 'Dieta';break;
                            case 'H' :$concepts[]  = 'Hotel';break;
                            case 'DB':$concepts[]  = 'Dinero de Bolsillo';break;
                            case 'I' :$concepts[]  = 'Imprevisto';break;
                            case 'IE':$concepts[]  = 'Inscripción en Evento';break;
                            case 'O' :$concepts[]  = 'Otros';break;
                            default:break;
                        }
                        $import     = $import + $economic->getAmount();
                    }
                }
            }

            $concepts = array_unique($concepts);

            $entries[] = [
                'client'    => $client,
                'ci'        => $ci,
                'area'      => $area,
                'category'  => $category,
                'concepts'   => $concepts,
                'objetives'  => $objetives,
                'countries'   => $countries,
                'exitDate'  => $exitDate,
                'lapsed'    => $lapsed,
                'import'    => number_format($import, 2,'.',',')
            ];

            $totalImport += $import;
        }

        $availability = Economic::INITIAL_BALANCE_OF_EVENT_ACOUNT - $totalImport;

        $result = [
            'entries' => $entries,
            'totalImport' => number_format($totalImport, 2,'.',','),
            'availability' => number_format($availability, 2,'.',',')
        ];

        return $result;
    }

    /**
     * Generate and save a Event Acount Report to Word
     * @Route("/prueba", name="exit_prueba_to_excel")
     *
     */
    public function prueba(){
        $template = "report_templates/30template.xls";
        $fileName = 'report_results/prueba.xls';

        $readerXls  = $this->get('phpoffice.spreadsheet')->createReader('Xls');
        $spreadsheet = $readerXls->load($template);

        $sheet = $spreadsheet->getActiveSheet();

        $data = [
            [
                'title' => 'Excel for dummies',
                'price' => 17.99,
                'quantity' => 2,
            ],
            [
                'title' => 'PHP for dummies',
                'price' => 15.99,
                'quantity' => 1,
            ],
            [
                'title' => 'Inside OOP',
                'price' => 12.95,
                'quantity' => 1,
            ],
        ];

        $sheet->setCellValue('D1', Date::PHPToExcel(time()));

        $baseRow = 5;
        foreach ($data as $r => $dataRow) {
            $row = $baseRow + $r;
            $sheet->insertNewRowBefore($row, 1);

            $sheet->setCellValue('A' . $row, $r + 1)
                ->setCellValue('B' . $row, $dataRow['title'])
                ->setCellValue('C' . $row, $dataRow['price'])
                ->setCellValue('D' . $row, $dataRow['quantity'])
                ->setCellValue('E' . $row, '=C' . $row . '*D' . $row);
        }
        $sheet->removeRow($baseRow - 1, 1);

        $writerXlsx = $this->get('phpoffice.spreadsheet')->createWriter($spreadsheet, 'Xls');
        $writerXlsx->save($fileName);
    }


    /**
     * Generate Event Acount Report
     *
     * @param Request $request
     * @param integer $year
     * @param integer $month
     *
     * @Route("/dcc2/{year}/{month}", name="exit_report_dcc2")
     * @Method({"GET", "POST"})
     *
     * @return Response
     */
    public function reportDCC2(Request $request, $year = null, $month = null){
        $em = $this->getDoctrine()->getManager();
        $appRepo = $em->getRepository('DRIExitBundle:Departure');

        $deps = $appRepo->getDepsDepartureDate();
        $years = array();

        foreach ($deps as $app){
            $years[date_format($app['departureDate'], 'Y')] = date_format($app['departureDate'], 'Y');
        }
        $months = [
            'Enero'         => '01',
            'Febrero'       => '02',
            'Marzo'         => '03',
            'Abril'         => '04',
            'Mayo'          => '05',
            'Junio'         => '06',
            'Julio'         => '07',
            'Agosto'        => '08',
            'Septiembre'    => '09',
            'Octubre'       => '10',
            'Noviembre'     => '11',
            'Diciembre'     => '12',
        ];

        if(!$year && !$month){
            $year = date_format(date_create(), 'Y');
            $month = date_format(date_create(), 'm')-1;
        }

        $year_form = $this->createFormBuilder()
            ->add('month', ChoiceType::class, array(
                'label' => false,
                'placeholder' => 'Seleccione el Mes',
                'choices'  => $months,
                'attr' => array(
                    'class'=>'bs-select',
                ),
            ))
            ->add('year', ChoiceType::class, array(
                'label' => false,
                'placeholder' => 'Seleccione el Año',
                'choices'  => $years,
                'attr' => array(
                    'class'=>'bs-select',
                ),
            ))
            ->getForm();
        $year_form->handleRequest($request);

        if ($year_form->isSubmitted() && $year_form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $year_form->getData();
            $month = $data['month'];
            $year = $data['year'];
        }


        $entries = $this->getDepsForMonth($year, $month);
        //$entries = $result['entries'];

        /*for ($key = 0, $size = count($entries); $key < $size; $key++) {
            $entries[$key]['concepts'] = join('<br>', $entries[$key]['concepts']);
            $entries[$key]['objetives'] = join('<br>', $entries[$key]['objetives']);
            $entries[$key]['countries'] = join('<br>', $entries[$key]['countries']);
        }*/

        $monthView = Useful::convertMonthNumberToName($month);

        return $this->render('DRIExitBundle:Reports:dcc2.html.twig', array(
            'month' => $month,
            'monthView' => $monthView,
            'year' => $year,
            'year_form' => $year_form->createView(),
            'entries' => $entries,
        ));
    }

    /**
     * Generate and save a Event Acount Report to Word
     *
     * @param integer $year
     * @param integer $month
     *
     * @Route("/dcc2-excel/{year}/{month}", name="exit_report_dcc2_to_excel")
     *
     * @return BinaryFileResponse
     */
    public function reportDCC2ToWord($year = null, $month = null)
    {
        $em = $this->getDoctrine()->getManager();

        $currentDate = sprintf('%s', date('d/m/Y'));
        $name = 'Reporte DCC2';
        $template = "report_templates/dcc2_template.xlsx";

        if ($year && $month){
            $filename = sprintf('%s - %s %s.docx', $name, $month, $year);
        }else{
            $year = sprintf('%s', date('Y'));
            $month = sprintf('%s', date('m')-1);
            $filename = sprintf('%s - %s %s.docx', $name, $month, $year);
        }

        $monthView = Useful::convertMonthNumberToName($month);

        $readerXls  = $this->get('phpoffice.spreadsheet')->createReader('Xlsx');
        $spreadsheet = $readerXls->load($template);

        $sheet = $spreadsheet->getActiveSheet();

        $entries = $this->getDepsForMonth($year, $month);

        $sheet->setCellValue('J3', Date::PHPToExcel(time()));
        $sheet->setCellValue('C4', $monthView);

        $baseRow = 10;
        foreach ($entries as $r => $dataRow) {
            $row = $baseRow + $r;
            $sheet->insertNewRowBefore($row, 1);

            $sheet
                ->setCellValue('B' . $row, $dataRow['client'])
                ->setCellValue('C' . $row, $dataRow['possition'])
                ->setCellValue('D' . $row, $dataRow['countries'])
                ->setCellValue('E' . $row, $dataRow['concepts'])
                ->setCellValue('F' . $row, $dataRow['exitDate'])
                ->setCellValue('G' . $row, $dataRow['lapsed'])
                ->setCellValue('H' . $row, $dataRow['institutions'])
                ->setCellValue('I' . $row, $dataRow['passage'])
                ->setCellValue('J' . $row, $dataRow['state'])
                ->setCellValue('K' . $row, $dataRow['objetives']);
        }
        $sheet->removeRow($baseRow - 1, 1);

        $writerXlsx = $this->get('phpoffice.spreadsheet')->createWriter($spreadsheet, 'Xlsx');
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writerXlsx->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $filename, ResponseHeaderBag::DISPOSITION_INLINE);
        /*
        $response = new BinaryFileResponse($templateOut);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;*/
    }

    public function getDepsForMonth($year, $month){
        $em = $this->getDoctrine()->getManager();
        $depRepo = $em->getRepository('DRIExitBundle:Departure');

        $monthEntries = $depRepo->getDepsForMonth($year, $month);



        $entries = array();

        foreach ($monthEntries as $entry){
            $app = $entry->getApplication();
            $client = $app->getClient();

            $missions = $app->getMissions();

            $clientName = $client->getFullName();

            if($client->getClientType() == 'est'){
                if($client->getStudentsPosition() != null){
                    $pos = $client->getStudentsPosition();
                }else{
                    $pos = 'Estudiante';
                }
            }else{
                if($client->getWorkersPosition() != null){
                    $pos = $client->getWorkersPosition();
                }else{
                    $pos = $client->getWorkersOccupation();
                }
            }

            $concepts     = array();
            $objetives    = array();
            $countries    = array();
            $institutions = array();
            $exitDate     = $entry->getDepartureDate()->format('d/m/Y');
            $lapsed       = $app->getLapsed();

            $passage = '';

            foreach ($missions as $mission){
                $countries[]  = $mission->getCountry()->getSpName();
                $concepts[]  = Useful::getExitConceptName($mission->getConcept());
                $objetives[] = $mission->getObjetives();
                $institutions[] = $mission->getInstitution();

                $economics = $mission->getEconomics();

                foreach ($economics as $economic){
                    if ($economic->getEventAcount() == true){
                        if ($economic->getType() == 'P' || $economic->getType() == 'PA'){
                            if($passage == ''){
                                $passage = $economic->getSource();
                            }
                            if ($passage != '' && $passage != $economic->getSource()){
                                $passage = 'MIXTA';
                            }
                        }
                    }
                }
            }

            $countries = join(' - ', array_unique($countries));
            $concepts = join(' - ', array_unique($concepts));
            $objetives = join(' - ', array_unique($objetives));
            $institutions = join(' - ', array_unique($institutions));

            $entries[] = [
                'client'        => $clientName,
                'possition'     => $pos,
                'countries'     => $countries,
                'concepts'      => $concepts,
                'exitDate'      => $exitDate,
                'lapsed'        => $lapsed,
                'institutions'  => $institutions,
                'passage'       => $passage,
                'state'         => 'OK',
                'objetives'     => $objetives,
            ];

        }

        return $entries;
    }
}
