<?php

namespace DRI\UsefulBundle\Controller;

use DRI\UsefulBundle\Datatables\CountryDatatable;

use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CountryController extends Controller
{
    /**
     * Lists all Country entities.
     *
     * @param Request $request
     *
     * @Route("/paises", name="paises_index")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.post');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(CountryDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DRIUsefulBundle:Country:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Lists all Country entities.
     *
     * @param Request $request
     *
     * @Route("/countries/list", name="countries_list")
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $countries = $em->getRepository('DRIUsefulBundle:Country')->findAll();

        return $this->render('DRIUsefulBundle:Country:list.html.twig', array(
            'countries' => $countries,
        ));
    }

    /**
     * Finds and displays a Country entity.
     *
     * @param Country $country
     *
     * @Route("/paises/{id}", name = "country_show")
     * @Method("GET")
     *
     * @return Response
     */
    public function showAction(Country $country)
    {
        return $this->render('country/show.html.twig', array(
            'country' => $country
        ));
    }

    /**
     * Finds and displays a Country entity.
     *
     * @param Country $country
     *
     * @Route("/paises/{id}", name = "country_edit")
     * @Method("GET")
     *
     * @return Response
     */
    public function editAction(Country $country)
    {
        return $this->render('country/show.html.twig', array(
            'country' => $country
        ));
    }
}
