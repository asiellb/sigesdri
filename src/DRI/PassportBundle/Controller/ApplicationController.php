<?php

namespace DRI\PassportBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use DRI\PassportBundle\Entity\Application;
use DRI\PassportBundle\Datatables\ApplicationDatatable;

use PhpParser\Node\Scalar\String_;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Application controller.
 *
 * @Route("/application")
 */
class ApplicationController extends Controller
{
    /**
     * Lists all Application entities.
     * @param Request $request
     *
     * @Route("/index", name="passport_application_index")
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

        return $this->render('DRIPassportBundle:Application:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Displays a form to create a new Application entity.
     *
     * @Route("/new", name="passport_application_new")
     * @Method({"GET", "POST"})
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

        $form   = $this->createForm('DRI\PassportBundle\Form\ApplicationNewType', $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();
            
            $editLink = $this->generateUrl('passport_application_edit', array('id' => $application->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>New application was created successfully.</a>" );

            return $this->redirectToRoute('passport_application_index');
        }
        return $this->render('@DRIPassport/Application/new.html.twig', array(
            'application' => $application,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a Application entity.
     *
     * @Route("/{id}", name="passport_application_show")
     * @Method("GET")
     */
    public function showAction(Application $application)
    {
        $deleteForm = $this->createDeleteForm($application);
        return $this->render('@DRIPassport/Application/show.html.twig', array(
            'application' => $application,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing application entity.
     *
     * @Route("/{id}/edit", name="passport_application_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Application $application)
    {
        $deleteForm = $this->createDeleteForm($application);
        $editForm = $this->createForm('DRI\PassportBundle\Form\ApplicationNewType', $application);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('passport_application_edit', array('id' => $application->getId()));
        }
        return $this->render('@DRIPassport/Application/edit.html.twig', array(
            'application' => $application,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Application entity.
     *
     * @Route("/{id}", name="passport_application_delete")
     * @Method("DELETE")
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
        
        return $this->redirectToRoute('passport_application_index');
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
            ->setAction($this->generateUrl('passport_application_delete', array('id' => $application->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Application by id
     *
     * @param mixed $id The entity id
     *
     * @Route("/delete/{id}", name="passport_application_by_id_delete")
     * @Method("GET")
     *
     * @return Response
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

        return $this->redirect($this->generateUrl('passport_application_index'));

    }

    /**
     * Bulk delete action.
     *
     * @param Request $request
     *
     * @Route("/bulk/delete", name="passport_application_bulk_delete")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function bulkDeleteAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $choices = $request->request->get('data');
            $token = $request->request->get('token');

            if (!$this->isCsrfTokenValid('multiselect', $token)) {
                throw new AccessDeniedException('El token CSRF no es valido.');
            }

            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('DRIPassportBundle:Application');

            $counter = 0;

            foreach ($choices as $choice) {
                $entity = $repository->find($choice['id']);
                $em->remove($entity);
                $counter++;
            }

            $em->flush();

            return new Response('Se borraron satisfactoriamente '.$counter.' aplicaciones', 200);
        }


        return new Response('Bad Request', 400);
    }
}
