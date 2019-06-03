<?php

namespace DRI\UsefulBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use DRI\UsefulBundle\Entity\Career;

/**
 * Career controller.
 *
 * @Route("/career")
 */
class CareerController extends Controller
{
    /**
     * Lists all Career entities.
     *
     * @Route("/", name="career")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('DRIUsefulBundle:Career')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($careers, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('DRIUsefulBundle:career:index.html.twig', array(
            'careers' => $careers,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,

        ));
    }


    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, $request)
    {
        $filterForm = $this->createForm('DRI\UsefulBundle\Form\CareerFilterType');

        // Bind values from the request
        $filterForm->handleRequest($request);

        if ($filterForm->isValid()) {
            // Build the query from the given form object
            $this->get('petkopara_multi_search.builder')->searchForm( $queryBuilder, $filterForm->get('search'));
        }

        return array($filterForm, $queryBuilder);
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
            return $me->generateUrl('career', $requestParams);
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
     * Displays a form to create a new Career entity.
     *
     * @Route("/new", name="career_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    
        $career = new Career();
        $form   = $this->createForm('DRI\UsefulBundle\Form\CareerType', $career);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($career);
            $em->flush();
            
            $editLink = $this->generateUrl('career_edit', array('id' => $career->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New career was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'career' : 'career_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('DRIUsefulBundle:career:new.html.twig', array(
            'career' => $career,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Career entity.
     *
     * @Route("/{id}", name="career_show")
     * @Method("GET")
     */
    public function showAction(Career $career)
    {
        $deleteForm = $this->createDeleteForm($career);
        return $this->render('DRIUsefulBundle:career:show.html.twig', array(
            'career' => $career,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Career entity.
     *
     * @Route("/{id}/edit", name="career_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Career $career)
    {
        $deleteForm = $this->createDeleteForm($career);
        $editForm = $this->createForm('DRI\UsefulBundle\Form\CareerType', $career);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($career);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('career_edit', array('id' => $career->getId()));
        }
        return $this->render('DRIUsefulBundle:career:edit.html.twig', array(
            'career' => $career,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Career entity.
     *
     * @Route("/{id}", name="career_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Career $career)
    {
    
        $form = $this->createDeleteForm($career);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($career);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Career was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Career');
        }
        
        return $this->redirectToRoute('career');
    }
    
    /**
     * Creates a form to delete a Career entity.
     *
     * @param Career $career The Career entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Career $career)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('career_delete', array('id' => $career->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Career by id
     *
     * @Route("/delete/{id}", name="career_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(Career $career){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($career);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Career was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Career');
        }

        return $this->redirect($this->generateUrl('career'));

    }
    

    /**
    * Bulk Action
    * @Route("/bulk-action/", name="career_bulk_action")
    * @Method("POST")
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('DRIUsefulBundle:Career');

                foreach ($ids as $id) {
                    $career = $repository->find($id);
                    $em->remove($career);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'careers was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the careers ');
            }
        }

        return $this->redirect($this->generateUrl('career'));
    }
    

}
