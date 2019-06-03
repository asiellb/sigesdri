<?php

namespace DRI\ForeingStudentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Constraints\Date;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Elastica\Exception\NotFoundException;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use PhpParser\Node\Scalar\String_;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;
use Sg\DatatablesBundle\Datatable\DatatableInterface;


use DRI\ForeingStudentBundle\Entity\Postgraduate;
use DRI\ForeingStudentBundle\Form\PostgraduateType;
use DRI\ForeingStudentBundle\Datatables\PostgraduateDatatable;
use DRI\UsefulBundle\Useful\Useful;

/**
 * Postgraduate controller.
 *
 * @Route("/postgraduate")
 */
class PostgraduateController extends Controller
{
    /**
     * Lists all Postgraduate entities.
     *
     * @Route("/", name="postgraduate_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('DRIForeingStudentBundle:Postgraduate')->createQueryBuilder('e');

        list($postgraduates, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('DRIForeingStudentBundle:Postgraduate:index.html.twig', array(
            'totalOfRecordsString' => $totalOfRecordsString,
            'postgraduates' => $postgraduates,
            'pagerHtml' => $pagerHtml,

        ));
    }


    /**
     * Lists all Postgraduate entities.
     * @param Request $request
     *
     * @Route("/list", name="postgraduate_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.client');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(PostgraduateDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DRIForeingStudentBundle:Postgraduate:list.html.twig', array(
            'datatable' => $datatable,
        ));
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
            return $me->generateUrl('postgraduate', $requestParams);
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
     * Displays a form to create a new Postgraduate entity.
     *
     * @Route("/new", name="postgraduate_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_FS_SPECIALIST')")
     */
    public function newAction(Request $request)
    {
        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $postgraduate = new Postgraduate();

        if ($user){
            $postgraduate->setCreatedBy($user);
        }

        $form   = $this->createForm('DRI\ForeingStudentBundle\Form\PostgraduateType', $postgraduate, [
            'currentAction' => 'new'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($postgraduate);
            $em->flush();

            $editLink = $this->generateUrl('postgraduate_edit', array('fullNameSlug' => $postgraduate->getFullNameSlug()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se ha creado un nuevo estudiante de posgrado.</a>" );

            return $this->redirectToRoute('postgraduate_show', array('fullNameSlug' => $postgraduate->getFullNameSlug()));
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('DRIForeingStudentBundle:Postgraduate:new.html.twig', array(
            'lastPage' => $lastVisited,
            'postgraduate' => $postgraduate,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Postgraduate entity.
     *
     * @Route("/profile/{fullNameSlug}", name="postgraduate_show", options = {"expose" = true})
     * @Method("GET")
     */
    public function showAction(Postgraduate $postgraduate)
    {
        $deleteForm = $this->createDeleteForm($postgraduate);
        return $this->render('DRIForeingStudentBundle:Postgraduate:show.html.twig', array(
            'postgraduate' => $postgraduate,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Postgraduate entity.
     *
     * @Route("/edit/{fullNameSlug}", name="postgraduate_edit", options = {"expose" = true})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_FS_SPECIALIST')")
     */
    public function editAction(Request $request, Postgraduate $postgraduate)
    {
        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        if ($user){
            $postgraduate->setLastUpdateBy($user);
        }

        $deleteForm = $this->createDeleteForm($postgraduate);
        $editForm = $this->createForm('DRI\ForeingStudentBundle\Form\PostgraduateType', $postgraduate, [
            'currentAction' => 'edit'
        ])->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($postgraduate);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'El Estudiante de Posgrado se editó satisfactoriamente!');
            return $this->redirectToRoute('postgraduate_edit', array('fullNameSlug' => $postgraduate->getFullNameSlug()));
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('DRIForeingStudentBundle:Postgraduate:edit.html.twig', array(
            'lastPage' => $lastVisited,
            'postgraduate' => $postgraduate,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Postgraduate entity.
     *
     * @Route("/{id}", name="postgraduate_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Postgraduate $postgraduate)
    {
    
        $form = $this->createDeleteForm($postgraduate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($postgraduate);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Postgraduate was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Postgraduate');
        }
        
        return $this->redirectToRoute('postgraduate_list');
    }
    
    /**
     * Creates a form to delete a Postgraduate entity.
     *
     * @param Postgraduate $postgraduate The Postgraduate entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Postgraduate $postgraduate)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('postgraduate_delete', array('id' => $postgraduate->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Postgraduate by id
     *
     * @Route("/delete/{id}", name="postgraduate_by_id_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteByIdAction(Postgraduate $postgraduate){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($postgraduate);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Postgraduate was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Postgraduate');
        }

        return $this->redirect($this->generateUrl('postgraduate_list'));

    }
    

    /**
    * Bulk Action
    * @Route("/bulk-action/", name="postgraduate_bulk_delete")
    * @Method("POST")
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
            $repository = $em->getRepository('DRIForeingStudentBundle:Postgraduate');

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
     *
     * @Route("/ci-available", name="postgraduate_ci_is_available", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @return JsonResponse|Response
     */
    public function isAvailableCIAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $ci = $request->request->get('ci');

            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository('DRIForeingStudentBundle:Postgraduate')->findOneByCi($ci);

            if(!$exist){
                return new JsonResponse(true);
            }
            else {
                return new JsonResponse(false);
            }
        }

    }

    /**
     * Available Email.
     *
     * @param Request $request
     *
     * @Route("/email-available", name="postgraduate_email_is_available", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @return JsonResponse|Response
     */
    public function isAvailableEmailAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $email = $request->request->get('email');

            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository('DRIForeingStudentBundle:Postgraduate')->findOneByEmail($email);

            if(!$exist){
                return new JsonResponse(true);
            }
            else {
                return new JsonResponse(false);
            }
        }

    }


    /**
     * Returns a JSON string with the dependencies of the CourseType.
     *
     * @param Request $request
     *
     * @Route("/list-postgraduate-courses-dependencies", name="list_postgraduate_courses_dependencies", options={"expose"=true})
     * @Method({"POST"})
     *
     * @return JsonResponse|Response
     */
    public function listCourseDependenciesAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();
        $em = $this->getDoctrine()->getManager();
        $courseRepo = $em->getRepository("DRIUsefulBundle:Course");

        if ($isAjax) {
            $courseType = $request->request->get('type');

            $courses = $courseRepo->createQueryBuilder("q")
                ->where("q.type = :type")
                ->setParameter("type", $courseType)
                ->getQuery()
                ->getResult();

            // Serialize into an array the data that we need, in this case only number and id
            // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
            $courseList = array();

            foreach ($courses as $cours) {
                $courseList[] = array(
                    "id"    => $cours->getId(),
                    "name"  => $cours->getName()
                );
            }

            //throw new Exception($institutionList);

            // Return array with structure of the passports of the providen client id
            return new JsonResponse(
                array(
                    'courses'   => $courseList
                )
            );
        }
    }


}
