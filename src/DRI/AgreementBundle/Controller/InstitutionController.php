<?php

namespace DRI\AgreementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use PhpParser\Node\Scalar\String_;
use Sg\DatatablesBundle\Datatable\DatatableInterface;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use DRI\AgreementBundle\Entity\Institution;
use DRI\AgreementBundle\Form\InstitutionType;
use DRI\AgreementBundle\Form\InstitutionFilterType;
use DRI\AgreementBundle\Datatables\InstitutionDatatable;

use DRI\UsefulBundle\Useful\Useful;


/**
 * Institution controller.
 *
 * @Route("/institution")
 */
class InstitutionController extends Controller
{
    /**
     * Lists all Institution entities.
     *
     * @Route("/index1", name="institution_index1")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('DRIAgreementBundle:Institution')->createQueryBuilder('e');
        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);

        list($institutions, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        return $this->render('institution/index.html.twig', array(
            'institutions' => $institutions,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),

        ));
    }

    /**
     * Lists all Institutions entities.
     *
     * @param Request $request
     *
     * @Route("/index", name="institution_index")
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
        $datatable = $this->get('sg_datatables.factory')->create(InstitutionDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DRIAgreementBundle:Institution:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    
    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter($queryBuilder, $request)
    {
        $session = $request->getSession();
        $filterForm = $this->createForm('DRI\AgreementBundle\Form\InstitutionFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('InstitutionControllerFilter');
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
                $session->set('InstitutionControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('InstitutionControllerFilter')) {
                $filterData = $session->get('InstitutionControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('DRI\AgreementBundle\Form\InstitutionFilterType', $filterData);
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
            return $me->generateUrl('institution', array('page' => $page));
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
     * Displays a form to create a new Institution entity.
     *
     * @Route("/new", name="institution_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST') or has_role('ROLE_MANAGE_SPECIALIST')")
     */
    public function newAction(Request $request)
    {
        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $institution = new Institution();

        if ($user){
            $institution->setCreatedBy($user);
        }

        $form   = $this->createForm('DRI\AgreementBundle\Form\InstitutionType', $institution, [
            'currentAction' => 'new'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($institution);
            $em->flush();
            
            $editLink = $this->generateUrl('institution_edit', array('id' => $institution->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se creó una nueva Institución Extranjera.</a>" );

            return $this->redirectToRoute('institution_index');
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('DRIAgreementBundle:Institution:new.html.twig', array(
            'lastPage' => $lastVisited,
            'institution' => $institution,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Institution entity.
     *
     * @Route("/{id}", name="institution_show", options = {"expose" = true})
     * @Method("GET")
     */
    public function showAction(Institution $institution)
    {
        $deleteForm = $this->createDeleteForm($institution);
        return $this->render('DRIAgreementBundle:Institution:show.html.twig', array(
            'institution' => $institution,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    

    /**
     * Displays a form to edit an existing Institution entity.
     *
     * @Route("/edit/{id}", name="institution_edit", options = {"expose" = true})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST') or has_role('ROLE_MANAGE_SPECIALIST')")
     */
    public function editAction(Request $request, Institution $institution)
    {
        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        if ($user){
            $institution->setLastUpdateBy($user);
        }

        $deleteForm = $this->createDeleteForm($institution);
        $editForm = $this->createForm('DRI\AgreementBundle\Form\InstitutionType', $institution, [
            'currentAction' => 'edit'
        ])->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($institution);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'La Institución Extranjera fué editada satisfactoriamente!');
            return $this->redirectToRoute('institution_show', array('id' => $institution->getId()));
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('DRIAgreementBundle:Institution:edit.html.twig', array(
            'lastPage' => $lastVisited,
            'institution' => $institution,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    

    /**
     * Deletes a Institution entity.
     *
     * @Route("/{id}", name="institution_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Institution $institution)
    {
    
        $form = $this->createDeleteForm($institution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($institution);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Institution was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Institution');
        }
        
        return $this->redirectToRoute('institution_index');
    }
    
    /**
     * Creates a form to delete a Institution entity.
     *
     * @param Institution $institution The Institution entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Institution $institution)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('institution_delete', array('id' => $institution->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Institution by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="institution_by_id_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteByIdAction(Institution $institution){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($institution);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Institution was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Institution');
        }

        return $this->redirect($this->generateUrl('institution_index'));

    }
    

    /**
    * Bulk Action
    * @Route("/bulk-action/", name="institution_bulk_delete")
    * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
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
            $repository = $em->getRepository('DRIAgreementBundle:Institution');

            $counter = 0;

            foreach ($choices as $choice) {
                $entity = $repository->find($choice['id']);
                $em->remove($entity);
                $counter++;
            }

            $em->flush();

            return new Response('Se borraron satisfactoriamente '.$counter.' Instituciones', 200);
        }


        return new Response('Solicitud incorrecta', 400);
    }
    

}
