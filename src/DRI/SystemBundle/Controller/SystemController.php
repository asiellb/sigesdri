<?php

namespace DRI\SystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use PHPExcel_Exception;


class SystemController extends Controller
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @return Response
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
     * @return Response
     */
    public function aboutAction()
    {
        return $this->render('DRISystemBundle:system:about.html.twig');
    }

    /**
     * @Route("/excel", name="excel")
     * @return StreamedResponse
     * @throws PHPExcel_Exception
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
