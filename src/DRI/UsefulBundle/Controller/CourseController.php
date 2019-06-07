<?php

namespace DRI\UsefulBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use Exception;

use DRI\UsefulBundle\Entity\Course;

/**
 * Course controller.
 *
 * @Route("/course")
 */
class CourseController extends Controller
{
    /**
     * Lists all Course entities.
     *
     * @param Request $request
     * @Route("/", name="course", methods={"GET"})
     * @return Response
     * @throws Exception
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository(Course::class)->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($courses, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('DRIUsefulBundle:course:index.html.twig', array(
            'courses' => $courses,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,

        ));
    }

    /**
     * Create filter form and process filter request.
     *
     * @param QueryBuilder $queryBuilder
     * @param Request $request
     * @return array
     */
    protected function filter($queryBuilder, Request $request)
    {
        $session = $request->getSession();
        $filterForm = $this->createForm('DRI\UsefulBundle\Form\CourseFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('CourseControllerFilter');
        }

        // Filter action
        if ($request->get('filter_action') == 'filter') {
            // Bind values from the request
            $filterForm->handleRequest($request);

            if ($filterForm->isValid()) {
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                // Save filter to session
                $filterData = $filterForm->getData();
                $session->set('CourseControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('CourseControllerFilter')) {
                $filterData = $session->get('CourseControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('DRI\UsefulBundle\Form\CourseFilterType', $filterData);
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
        } catch (OutOfRangeCurrentPageException $ex) {
            $pagerfanta->setCurrentPage(1);
        }
        
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me, $request)
        {
            $requestParams = $request->query->all();
            $requestParams['pcg_page'] = $page;
            return $me->generateUrl('course', $requestParams);
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
     * Calculates the total of records string
     *
     * @param QueryBuilder $queryBuilder
     * @param Request $request
     * @return string
     * @throws Exception
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
     * Displays a form to create a new Course entity.
     *
     * @param Request $request
     * @Route("/new", name="course_new", methods={"GET", "POST"})
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $course = new Course();
        $form   = $this->createForm('DRI\UsefulBundle\Form\CourseType', $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();
            
            $editLink = $this->generateUrl('course_edit', array('id' => $course->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New course was created successfully.</a>" );
            
            $nextAction=  $request->get('submit') == 'save' ? 'course' : 'course_new';
            return $this->redirectToRoute($nextAction);
        }
        return $this->render('DRIUsefulBundle:course:new.html.twig', array(
            'course' => $course,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Course entity.
     *
     * @param Course $course
     * @Route("/{id}", name="course_show", methods={"GET"})
     * @return Response
     */
    public function showAction(Course $course)
    {
        $deleteForm = $this->createDeleteForm($course);
        return $this->render('DRIUsefulBundle:course:show.html.twig', array(
            'course' => $course,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Course entity.
     *
     * @param Request $request
     * @param Course $course
     * @Route("/{id}/edit", name="course_edit", methods={"GET", "POST"})
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Course $course)
    {
        $deleteForm = $this->createDeleteForm($course);
        $editForm = $this->createForm('DRI\UsefulBundle\Form\CourseType', $course);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('course_edit', array('id' => $course->getId()));
        }
        return $this->render('DRIUsefulBundle:course:edit.html.twig', array(
            'course' => $course,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Course entity.
     *
     * @param Request $request
     * @param Course $course
     * @Route("/{id}", name="course_delete", methods={"DELETE"})
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Course $course)
    {
        $form = $this->createDeleteForm($course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($course);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Course was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Course');
        }
        
        return $this->redirectToRoute('course');
    }
    
    /**
     * Creates a form to delete a Course entity.
     *
     * @param Course $course The Course entity
     * @return FormInterface The form
     */
    private function createDeleteForm(Course $course)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('course_delete', array('id' => $course->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Delete Course by id
     *
     * @param Course $course
     * @Route("/delete/{id}", name="course_by_id_delete", methods={"GET"})
     * @return RedirectResponse
     */
    public function deleteByIdAction(Course $course){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($course);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Course was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Course');
        }

        return $this->redirect($this->generateUrl('course'));

    }

    /**
     * Bulk Action
     *
     * @param Request $request
     * @Route("/bulk-action/", name="course_bulk_action", methods={"POST"})
     * @return RedirectResponse
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('Course');

                foreach ($ids as $id) {
                    $course = $repository->find($id);
                    $em->remove($course);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'courses was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the courses ');
            }
        }

        return $this->redirect($this->generateUrl('course'));
    }
}
