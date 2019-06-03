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


// Include the BinaryFileResponse and the ResponseHeaderBag
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

// Include the requires classes of Phpword
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\TemplateProcessor;


use DRI\AgreementBundle\Entity\Application;
use DRI\AgreementBundle\Datatables\ApplicationDatatable;
use DRI\AgreementBundle\Form\ApplicationType;
use DRI\AgreementBundle\Form\ApplicationFilterType;
use DRI\AgreementBundle\Datatables\InstitutionDatatable;
use DRI\UsefulBundle\Useful\Useful;



/**
 * Application controller.
 *
 * @Route("/application")
 */
class ApplicationController extends Controller
{
    /**
     * Lists all Institutional Agreements entities.
     *
     * @param Request $request
     *
     * @Route("/index", name="agreement_application_index")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.client');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(ApplicationDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DRIAgreementBundle:Application:index.html.twig', array(
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
        $filterForm = $this->createForm('DRI\AgreementBundle\Form\ApplicationFilterType');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('ApplicationControllerFilter');
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
                $session->set('ApplicationControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('ApplicationControllerFilter')) {
                $filterData = $session->get('ApplicationControllerFilter');
                
                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }
                
                $filterForm = $this->createForm('DRI\AgreementBundle\Form\ApplicationFilterType', $filterData);
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
            return $me->generateUrl('application', array('page' => $page));
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
     * Displays a form to create a new Application entity.
     *
     * @Route("/new", name="agreement_application_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MANAGE_SPECIALIST')")
     */
    public function newAction(Request $request)
    {
        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $application = new Application();

        if ($user){
            $application->setCreatedBy($user);
        }

        $form   = $this->createForm('DRI\AgreementBundle\Form\ApplicationType', $application, [
            'currentAction' => 'new'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();
            
            $editLink = $this->generateUrl('agreement_application_edit', array('id' => $application->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se creó una nueva Ficha de Consulta de Convenios.</a>" );

            return $this->redirectToRoute('agreement_application_show', array('id' => $application->getId()));
        }

        $lastVisited = $request->server->get('HTTP_REFERER');


        return $this->render('DRIAgreementBundle:Application:new.html.twig', array(
            'lastPage' => $lastVisited,
            'application' => $application,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Application entity.
     *
     * @Route("/{id}", name="agreement_application_show", options = {"expose" = true})
     * @Method("GET")
     */
    public function showAction(Application $application)
    {
        $deleteForm = $this->createDeleteForm($application);
        return $this->render('DRIAgreementBundle:Application:show.html.twig', array(
            'application' => $application,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Application entity.
     *
     * @Route("/edit/{id}", name="agreement_application_edit", options = {"expose" = true})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MANAGE_SPECIALIST')")
     */
    public function editAction(Request $request, Application $application)
    {
        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        if ($user){
            $application->setLastUpdateBy($user);
        }

        $deleteForm = $this->createDeleteForm($application);
        $editForm = $this->createForm('DRI\AgreementBundle\Form\ApplicationType', $application, [
            'currentAction' => 'edit'
        ])->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'La Ficha de Consulta fué editada satisfactoriamente!');

            return $this->redirectToRoute('agreement_application_show', array('id' => $application->getId()));
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('DRIAgreementBundle:Application:/edit.html.twig', array(
            'lastPage' => $lastVisited,
            'application' => $application,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a Application entity.
     *
     * @Route("/{id}", name="agreement_application_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Application $application)
    {
    
        $form = $this->createDeleteForm($application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($application);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Application was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Application');
        }
        
        return $this->redirectToRoute('application');
    }
    
    /**
     * Creates a form to delete a Application entity.
     *
     * @param Application $application The Application entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Application $application)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('agreement_application_delete', array('id' => $application->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Application by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="agreement_application_by_id_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteByIdAction(Application $application){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($application);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Application was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Application');
        }

        return $this->redirect($this->generateUrl('application'));

    }
    

    /**
    * Bulk Action
    * @Route("/bulk-action/", name="agreement_application_bulk_delete")
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
            $repository = $em->getRepository('DRIAgreementBundle:Application');

            $counter = 0;

            foreach ($choices as $choice) {
                $entity = $repository->find($choice['id']);
                $em->remove($entity);
                $counter++;
            }

            $em->flush();

            return new Response('Se borraron satisfactoriamente '.$counter.' Fichas de Consulta', 200);
        }


        return new Response('Solicitud incorrecta', 400);
    }

    /**
     * Generate and save a ManagerTravelPlan to Word
     *
     * @Route("/to/word/{id}", name="agreement_application_to_word")
     */
    public function applicationToWordAction(Application $application)
    {
        $em = $this->getDoctrine()->getManager();

        $currentPeriod = Useful::getCurrentPeriod();
        $currentDate = sprintf('%s', date('d/m/Y'));

        $name = 'Ficha de Consulta de Convenios ';
        $filename = sprintf('%s - %s.docx', $name, $application->getNumber());
        $template = "report_templates/agreements_consult_sheet.docx";

        //Vars declaration
        $ces = 'Universidad de Camagüey';
        $institution = $application->getInstitution()->getName();
        $address = $application->getInstitution()->getAddress();
        $background = $application->getBackground();
        $objetives = $application->getObjetives();
        $basement = $application->getBasement();
        $commitments = $application->getCommitments();
        $validity = $application->getValidity();
        $memberForCubanPart = $application->getMemberForCubanPart();
        $memberForForeignPart = $application->getMemberForForeignPart();
        $results = $application->getResults();
        $expenses = $application->getExpenses().' CUC';


        //var_dump($agreementsArray);
        // ask the service for a Word2007
        $templateObj = $this->get('phpword')->createTemplateObject($template);

        $templateObj->setValue('currentPeriod', $currentPeriod);
        $templateObj->setValue('currentDate', $currentDate);
        $templateObj->setValue('ces', $ces);
        $templateObj->setValue('institution', $institution);
        $templateObj->setValue('address', $address);
        $templateObj->setValue('background', $background);
        $templateObj->setValue('objetives', $objetives);
        $templateObj->setValue('basement', $basement);
        $templateObj->setValue('commitments', $commitments);
        $templateObj->setValue('validity', $validity);
        $templateObj->setValue('memberForCubanPart', $memberForCubanPart);
        $templateObj->setValue('memberForForeignPart', $memberForForeignPart);
        $templateObj->setValue('results', $results);
        $templateObj->setValue('expenses', $expenses);


        $templateOut = $templateObj->save();

        $response = new BinaryFileResponse($templateOut);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }
}
