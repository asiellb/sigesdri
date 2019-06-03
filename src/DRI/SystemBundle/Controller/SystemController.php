<?php

namespace DRI\SystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


class SystemController extends Controller
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clientsCount = $em->getRepository('DRIClientBundle:Client')->findAll();
        $passportsCount = $em->getRepository('DRIPassportBundle:Passport')->findAll();
        $agreementsCount = $em->getRepository('DRIAgreementBundle:Institutional')->findAll();

        return $this->render('DRISystemBundle:system:index.html.twig', [
            'clients' => $clientsCount,
            'passports' => $passportsCount,
            'agreements' => $agreementsCount,
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render('DRISystemBundle:system:about.html.twig');
    }

    /**
     * @Route("/excel", name="excel")
     */
    public function excelAction()
    {
        // Solicita el servicio de excel
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("liuggio")
            ->setLastModifiedBy("Giulio De Donato")
            ->setTitle("Office 2005 XLSX Test Document")
            ->setSubject("Office 2005 XLSX Test Document")
            ->setDescription("Test document for Office 2005 XLSX, generado usando clases de PHP")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Archivo de ejemplo");
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hola')
            ->setCellValue('B2', 'Mundo!');
        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        // Define el indice de página al número 1, para abrir esa página al abrir el archivo
        $phpExcelObject->setActiveSheetIndex(0);

        // Crea el writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        // Envia la respuesta del controlador
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // Agrega los headers requeridos
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'PhpExcelFileSample.xlsx'
        );

        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }
}
