<?php

namespace DRI\UsefulBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Exception;

use DRI\UsefulBundle\Entity\Country;

class CountryController extends Controller
{
    /**
     * Lists all Country entities.
     *
     * @param Request $request
     * @Route("/paises", name="paises_index", methods={"GET"})
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function indexAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('app.datatable.useful.country');
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
     * @Route("/countries/list", name="countries_list")
     * @return Response
     */
    public function listAction()
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
     * @Route("/paises/{id}", name = "country_show", methods={"GET"})
     * @return Response
     */
    public function showAction(Country $country)
    {
        return $this->render('DRIUsefulBundle:country:show.html.twig', array(
            'country' => $country
        ));
    }

    /**
     * Finds and displays a Country entity.
     *
     * @param Country $country
     * @Route("/paises/{id}", name = "country_edit", methods={"GET"})
     * @return Response
     */
    public function editAction(Country $country)
    {
        return $this->render('DRIUsefulBundle:country:show.html.twig', array(
            'country' => $country
        ));
    }
}
