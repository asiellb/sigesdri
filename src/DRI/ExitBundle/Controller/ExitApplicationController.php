<?php

namespace DRI\ExitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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

use DRI\ExitBundle\Entity\ExitApplication;
use DRI\ExitBundle\Datatables\ExitApplicationDatatable;
use DRI\ExitBundle\Form\ExitApplicationType;

/**
 * ExitApplication controller.
 *
 * @Route("/application")
 */
class ExitApplicationController extends Controller
{
    /**
     * Lists all Application entities.
     * @param Request $request
     *
     * @Route("/index", name="exit_application_index")
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
        $datatable = $this->get('sg_datatables.factory')->create(ExitApplicationDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DRIExitBundle:Application:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Displays a form to create a new ExitApplication entity.
     *
     * @Route("/new", name="exit_application_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $exitApplication = new ExitApplication();
        if ($user){
            $exitApplication->setCreatedBy($user);
            $exitApplication->setLastUpdateBy($user);
        }

        $form   = $this->createForm('DRI\ExitBundle\Form\ExitApplicationType', $exitApplication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($exitApplication);
            $em->flush();
            
            $editLink = $this->generateUrl('exit_application_edit', array('id' => $exitApplication->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se creó una nueva Solicitud de Salida.</a>" );

            return $this->redirectToRoute('exit_application_index');
        }
        return $this->render('DRIExitBundle:Application:new.html.twig', array(
            'exitApplication' => $exitApplication,
            'form'   => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a ExitApplication entity.
     *
     * @Route("/{id}", name="exit_application_show")
     * @Method("GET")
     */
    public function showAction(ExitApplication $exitApplication)
    {
        $deleteForm = $this->createDeleteForm($exitApplication);
        return $this->render('DRIExitBundle:Application:show.html.twig', array(
            'exitApplication' => $exitApplication,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing ExitApplication entity.
     *
     * @Route("/{id}/edit", name="exit_application_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ExitApplication $exitApplication)
    {
        $deleteForm = $this->createDeleteForm($exitApplication);
        $editForm = $this->createForm('DRI\ExitBundle\Form\ExitApplicationType', $exitApplication);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($exitApplication);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');
            return $this->redirectToRoute('exit_application_edit', array('id' => $exitApplication->getId()));
        }
        return $this->render('DRIExitBundle:Application:edit.html.twig', array(
            'exitApplication' => $exitApplication,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Deletes a ExitApplication entity.
     *
     * @Route("/{id}", name="exit_application_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ExitApplication $exitApplication)
    {
    
        $form = $this->createDeleteForm($exitApplication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($exitApplication);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The ExitApplication was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the ExitApplication');
        }
        
        return $this->redirectToRoute('exit_applications');
    }
    
    /**
     * Creates a form to delete a ExitApplication entity.
     *
     * @param ExitApplication $exitApplication The ExitApplication entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ExitApplication $exitApplication)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('exit_application_delete', array('id' => $exitApplication->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete ExitApplication by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="exit_application_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(ExitApplication $exitApplication){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($exitApplication);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The ExitApplication was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the ExitApplication');
        }

        return $this->redirect($this->generateUrl('exit_applications'));

    }

    /**
     * Bulk delete action.
     *
     * @param Request $request
     *
     * @Route("/bulk/delete", name="exit_application_bulk_delete")
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

    /**
     * Muestra información del cliente
     *
     * @param Request $request
     *
     * @Route("/", name="exit_application_client_show")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function clientShowAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest())
        {
            $encoders = array(new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);

            $client_id = $request->query->get('client_id');

            $client_id = (int)$client_id;

            $clientRepository = $em->getRepository('DRIClientBundle:Client')->findOneById($client_id);

            $client = [
                'clientSchool'  =>  $clientRepository->getSchool()->getName(),
                'clientPicture' =>  $clientRepository->getClientPicture(),
            ];
            $response = new JsonResponse();
            $response->setStatusCode(200);
            $response->setData(array(
                'response' => 'success',
                'client' => $serializer->serialize($client, 'json')
            ));
            return $response;
        }
    }
}
