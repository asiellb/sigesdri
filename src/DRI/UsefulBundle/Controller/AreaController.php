<?php

namespace DRI\UsefulBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use Exception;

use DRI\UsefulBundle\Entity\Area;

/**
 * Area controller.
 *
 * @Route("/area")
 */
class AreaController extends Controller
{
    /**
     * Lists all Area entities.
     *
     * @param Request $request
     * @Route("/", name="area", methods={"GET"})
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('DRIUsefulBundle:Area')->createQueryBuilder('e');
        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);

        list($areas, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('DRIUsefulBundle:area:index.html.twig', array(
            'areas' => $areas,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),

        ));
    }

    /**
     * Create filter form and process filter request.
     *
     * @param QueryBuilder $queryBuilder
     * @param Request $request
     * @return array
     */
    protected function filter($queryBuilder, $request)
    {
        $session = $request->getSession();
        $filterForm = $this->createForm('DRI\UsefulBundle\Form\AreaFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('AreaControllerFilter');
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
                $session->set('AreaControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('AreaControllerFilter')) {
                $filterData = $session->get('AreaControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('DRI\UsefulBundle\Form\AreaFilterType', $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return array($filterForm, $queryBuilder);
    }

    /**
     * Get results from paginator and get paginator view.
     *
     * @param QueryBuilder $queryBuilder
     * @param Request $request
     * @return array
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
            return $me->generateUrl('area', array('page' => $page));
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

    /**
     * Displays a form to create a new Area entity.
     *
     * @param Request $request
     * @Route("/new", name="area_new", methods={"GET", "POST"})
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $area = new Area();
        $form   = $this->createForm('DRI\UsefulBundle\Form\AreaType', $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($area);
            $em->flush();
            
            $editLink = $this->generateUrl('area_edit', array('id' => $area->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se creó una nueva area con exito.</a>" );

            return $this->redirectToRoute('area');
        }
        return $this->render('DRIUsefulBundle:area:new.html.twig', array(
            'area' => $area,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Area entity.
     *
     * @param Area $area
     * @Route("/{id}", name="area_show", methods={"GET"})
     * @return Response
     */
    public function showAction(Area $area)
    {
        $deleteForm = $this->createDeleteForm($area);
        return $this->render('DRIUsefulBundle:area:show.html.twig', array(
            'area' => $area,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Area entity.
     *
     * @param Request $request
     * @param Area $area
     * @Route("/{id}/edit", name="area_edit", methods={"GET", "POST"})
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Area $area)
    {
        $deleteForm = $this->createDeleteForm($area);
        $editForm = $this->createForm('DRI\UsefulBundle\Form\AreaType', $area);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($area);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('area_edit', array('id' => $area->getId()));
        }
        return $this->render('DRIUsefulBundle:area:edit.html.twig', array(
            'area' => $area,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Area entity.
     *
     * @param Request $request
     * @param Area $area
     * @Route("/{id}", name="area_delete", methods={"DELETE"})
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Area $area)
    {
    
        $form = $this->createDeleteForm($area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($area);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'El Area se borró con éxito!');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Hay un problema al borrar el Area');
        }
        
        return $this->redirectToRoute('area');
    }
    
    /**
     * Creates a form to delete a Area entity.
     *
     * @param Area $area The Area entity
     * @return FormInterface The form
     */
    private function createDeleteForm(Area $area)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('area_delete', array('id' => $area->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Delete Area by id
     *
     * @param Area $area
     * @Route("/delete/{id}", name="area_by_id_delete", methods={"GET"})
     * @return RedirectResponse
     */
    public function deleteByIdAction(Area $area){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($area);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'El Area se borró con éxito');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Hay un problema al borrar el Area');
        }

        return $this->redirect($this->generateUrl('area'));

    }

    /**
     * Bulk Action
     * @param Request $request
     * @Route("/bulk-action/", name="area_bulk_action", methods={"POST"})
     * @return RedirectResponse
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('DRIUsefulBundle:Area');

                foreach ($ids as $id) {
                    $area = $repository->find($id);
                    $em->remove($area);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'Se borraron las áreas satisfactoriamente!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Existe un problema al borrar estas areas ');
            }
        }

        return $this->redirect($this->generateUrl('area'));
    }

    /**
     * Returns a JSON string with the dependencies of the Area with the providen id.
     *
     * @param Request $request
     * @Route("/list-area-career-dependencies", name="list_area_career_dependencies", options={"expose"=true}, methods={"POST"})
     * @return JsonResponse|Response
     */
    public function listAreaDependenciesAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();
        $em = $this->getDoctrine()->getManager();
        $areaRepo = $em->getRepository("DRIUsefulBundle:Area");

        if ($isAjax) {
            $areaParam = $request->request->get('area');
            $areaParam = (int)$areaParam;

            $area = $areaRepo->findOneBy(['id'],$areaParam);

            if(!$area){
                throw $this->createNotFoundException("No existe el Área.");
            }

            // Search the Careers that belongs to the Area with the given id as GET parameter "area"
            $careers = $area->getCareers();

            // Serialize into an array the data that we need, in this case only number and id
            // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
            $careersList = array();

            foreach ($careers as $career) {
                $careersList[] = [
                    "id"   => $career->getId(),
                    "name" => $career->getName()
                ];
            }

            //throw new Exception($institutionList);

            // Return array with structure of the passports of the providen client id
            return new JsonResponse(
                array(
                    'careers'     => $careersList
                )
            );
        }

        return new Response('Petición Falleda', Response::HTTP_BAD_REQUEST);
    }
}
