<?php

namespace Dri\TramitesBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;
use Pagerfanta\View\TwitterBootstrapView;
use Pagerfanta\View\DefaultView;

use Dri\TramitesBundle\Entity\Pasaporte;
use Dri\TramitesBundle\Form\PasaporteType;

use Dri\TramitesBundle\Form\PasaporteFilterType;

use Dri\TramitesBundle\Datatables\PasaporteDatatable;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


/**
 * Pasaporte controller.
 *
 * @Route("/pasaporte")
 */
class PasaporteController extends Controller
{
    /**
     * Lists all Pasaporte entities.
     *
     * @param Request $request
     *
     * @Route("/passdt", name="passdt_index")
     * @Method("GET")
     *
     * @return Response
     */
    public function dtAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('DriTramitesBundle:Pasaporte')->createQueryBuilder('e');
        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);

        list($pasaportes, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $records = array();

        foreach($pasaportes as $i) {
            $records[] = [
                'id' => $i->getId(),
                'no' => $i->getNoPas(),
                'ti' => $i->getTitular()->getNombreCompleto()
            ];
        }

        $ajaxList = $records;

        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.post');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(PasaporteDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DriTramitesBundle:Pasaporte:passdt.html.twig', array(
            'datatable' => $datatable,
            'pasaportes' => $pasaportes,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'ajaxList' => $ajaxList,
        ));
    }


    /**
     * Lists all Pasaporte entities.
     *
     * @Route("/", name="trm_pass_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('DriTramitesBundle:Pasaporte')->createQueryBuilder('e');
        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);

        list($pasaportes, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $records = array();

        foreach($pasaportes as $i) {
            $records[] = [
                'id' => $i->getId(),
                'no' => $i->getNoPas(),
                'ti' => $i->getTitular()->getNombreCompleto()
            ];
        }

        $ajaxList = $records;
        
        return $this->render('DriTramitesBundle:Pasaporte:pasaporte/list.html.twig', array(
            'pasaportes' => $pasaportes,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'ajaxList' => $ajaxList,
        ));
    }

    /**
     * Lists all Pasaporte entities.
     *
     * @Route("/ajax-list", name="trm_pass_index_ajax")
     *
     */
    public function indexAjaxAction(Request $request){
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $em = $this->getDoctrine()->getManager();
        $pasaportes = $em->getRepository('DriTramitesBundle:Pasaporte')->findAll();

        $iTotalRecords = count($pasaportes);
        $iDisplayLength = intval($request->query->get('length'));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($request->query->get('start'));
        $sEcho = intval($request->query->get('draw'));

        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        foreach($pasaportes as $pas){
            $records["data"][] = [
                '<input type="checkbox" name="id[]" value="'.$pas->getId().'">',
                $pas->getNoPas(),
                $pas->getTipoPas(),
                $pas->getTitular()->getNombreCompleto(),
                $pas->getEstadoPas(),
                '<a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> View</a>',
            ];
        }
        /*
        for($i = $iDisplayStart; $i < $end; $i++) {
            $records["data"][] = [
                '<input type="checkbox" name="id[]" value="'.$pasaportes[$i]->getId().'">',
                $pasaportes[$i]->getNoPas(),
                $pasaportes[$i]->getTipoPas(),
                $pasaportes[$i]->getTitular()->getNombreCompleto(),
                $pasaportes[$i]->getEstadoPas(),
                '<a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> View</a>',
            ];
        }
        */
        if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        $response = new JsonResponse();
        $response->setStatusCode(200);
        $response->setData(array(
            'response' => 'success',
            'personas' => $serializer->serialize($records, 'json')
        ));
        return $response;

        /*$iTotalRecords = 178;
        $iDisplayLength = intval($request->query->get('length'));
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($request->query->get('start'));
        $sEcho = intval($request->query->get('draw'));

        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $status_list = array(
            array("success" => "Pending"),
            array("info" => "Closed"),
            array("danger" => "On Hold"),
            array("warning" => "Fraud")
        );

        for($i = $iDisplayStart; $i < $end; $i++) {
            $status = $status_list[rand(0, 2)];
            $id = ($i + 1);
            $records["data"][] = array(
                '<input type="checkbox" name="id[]" value="'.$id.'">',
                $id,
                '12/09/2013',
                'Jhon Doe',
                'Jhon Doe',
                '450.60$',
                rand(1, 10),
                '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                '<a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> View</a>',
            );
        }

        if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        $response = new JsonResponse(
            array($records), 200);

        return $response;*/
    }

    
    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, $request)
    {
        $session = $request->getSession();
        $filterForm = $this->createForm('Dri\TramitesBundle\Form\PasaporteFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('PasaporteControllerFilter');
        }

        // Filter action
        if ($request->get('filter_action') == 'filter') {
            // Bind values from the request
            $filterForm->submit($request->query->get($filterForm->getName()));

            if ($filterForm->isValid()) {
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                // Save filter to session
                $filterData = $filterForm->getData();
                $session->set('PasaporteControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('PasaporteControllerFilter')) {
                $filterData = $session->get('PasaporteControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('Dri\TramitesBundle\Form\PasaporteFilterType', $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return array($filterForm, $queryBuilder);
    }

    /**
    * Get results from paginator and get paginator view.
    *
    */
    protected function paginator($queryBuilder, $request)
    {
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $request->get('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me)
        {
            return $me->generateUrl('trm_pass_index', array('page' => $page));
        };

        // Paginator - view
        $view = new TwitterBootstrap3View();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => '<i class="fa fa-angle-left"></i>',
            'next_message' => '<i class="fa fa-angle-right"></i>',
        ));

        return array($entities, $pagerHtml);
    }
    
    

    /**
     * Displays a form to create a new Pasaporte entity.
     *
     * @Route("/new", name="pasaporte_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    
        $pasaporte = new Pasaporte();
        $form   = $this->createForm('Dri\TramitesBundle\Form\PasaporteType', $pasaporte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pasaporte);
            $em->flush();
            
            $editLink = $this->generateUrl('pasaporte_edit', array('id' => $pasaporte->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se ha creado un nuevo Pasaporte de forma satisfactoria.</a>" );

            return $this->redirectToRoute('trm_pass_index');
        }
        return $this->render('DriTramitesBundle:Pasaporte:pasaporte/new.html.twig', array(
            'pasaporte' => $pasaporte,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Pasaporte entity.
     *
     * @Route("/{id}", name="pasaporte_show")
     * @Method("GET")
     */
    public function showAction(Pasaporte $pasaporte)
    {
        $deleteForm = $this->createDeleteForm($pasaporte);
        return $this->render('DriTramitesBundle:Pasaporte:pasaporte/show.html.twig', array(
            'pasaporte' => $pasaporte,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Pasaporte entity.
     *
     * @Route("/{id}/edit", name="pasaporte_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Pasaporte $pasaporte)
    {
        $deleteForm = $this->createDeleteForm($pasaporte);
        $editForm = $this->createForm('Dri\TramitesBundle\Form\PasaporteType', $pasaporte);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pasaporte);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('pasaporte_edit', array('id' => $pasaporte->getId()));
        }
        return $this->render('DriTramitesBundle:Pasaporte:pasaporte/edit.html.twig', array(
            'pasaporte' => $pasaporte,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Pasaporte entity.
     *
     * @Route("/{id}", name="pasaporte_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Pasaporte $pasaporte)
    {
    
        $form = $this->createDeleteForm($pasaporte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pasaporte);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Pasaporte was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Pasaporte');
        }
        
        return $this->redirectToRoute('trm_pass_index');
    }
    
    /**
     * Creates a form to delete a Pasaporte entity.
     *
     * @param Pasaporte $pasaporte The Pasaporte entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Pasaporte $pasaporte)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pasaporte_delete', array('id' => $pasaporte->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Pasaporte by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="pasaporte_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(Pasaporte $pasaporte){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($pasaporte);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Pasaporte was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Pasaporte');
        }

        return $this->redirect($this->generateUrl('trm_pass_index'));

    }
    

    /**
    * Bulk Action
    * @Route("/bulk-action/", name="pasaporte_bulk_action")
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('DriTramitesBundle:Pasaporte');

                foreach ($ids as $id) {
                    $pasaporte = $repository->find($id);
                    $em->remove($pasaporte);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'pasaportes was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the pasaportes ');
            }
        }

        return $this->redirect($this->generateUrl('trm_pass_index'));
    }


    /**
     * Get all Users from Database to show in Select-Filter.
     *
     * @param Request $request
     *
     * @Route("/usernames", name="select_usernames")
     *
     * @return JsonResponse|Response
     */
    public function selectCreatedByUsersnames(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('DriUsuarioBundle:Usuario')->findAll();

            $result = array();

            foreach ($users as $user) {
                $result[$user->getId()] = $user->getUsername();
            }

            return new JsonResponse($result);
        }

        return new Response('Bad request.', 400);
    }

}
