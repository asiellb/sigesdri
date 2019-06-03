<?php

namespace DRI\PassportBundle\Controller;


use DRI\PassportBundle\Datatables\ControlDatatable;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use DRI\PassportBundle\Entity\Control;
use DRI\PassportBundle\Form\ControlType;
use DRI\PassportBundle\Datatables\ApplicationDatatable;
use DRI\UsefulBundle\Useful\Useful;

/**
 * Control controller.
 *
 * @Route("/control")
 */
class ControlController extends Controller
{
    /**
     * Lists all Application entities.
     * @param Request $request
     *
     * @Route("/index", name="passport_control_index")
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
        $datatable = $this->get('sg_datatables.factory')->create(ControlDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DRIPassportBundle:Control:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Displays a form to create a new Control entity.
     *
     * @Route("/new/{passport}", name="passport_control_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_INFO_SPECIALIST')")
     */
    public function newAction(Request $request, $passport = null)
    {

        $user = null;
        $pass = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $em = $this->getDoctrine()->getManager();
        $passRepo = $em->getRepository('DRIPassportBundle:Passport');

        $control = new Control();

        if ($user){
            $control->setCreatedBy($user);
        }

        if($passport){

            $pass = $passRepo->find($passport);

            if(!$pass){
                throw $this->createNotFoundException("El pasaporte al que se pretende controlar no existe.");
            }
            $control->setPassport($pass);
        }

        $form   = $this->createForm('DRI\PassportBundle\Form\ControlType', $control, [
            'currentAction' => 'new'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!$pass){
                $pass = $passRepo->find($control->getPassport());
            }

            if ($control->getPickUpDate() == null){
                $pass->setInStore(false);
            }else{
                $pass->setInStore(true);
            }

            $em->persist($control);
            $em->flush();

            $editLink = $this->generateUrl('passport_control_edit', array('numberSlug' => $control->getNumberSlug()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se creó un nuevo control de Pasaporte.</a>" );

            return $this->redirectToRoute('passport_control_index');
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('@DRIPassport/Control/new.html.twig', array(
            'lastPage' => $lastVisited,
            'passport' => $pass,
            'control' => $control,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Control entity.
     *
     * @Route("/{numberSlug}", name="passport_control_show")
     * @Method("GET")
     */
    public function showAction(Control $control)
    {
        $deleteForm = $this->createDeleteForm($control);
        return $this->render('@DRIPassport/Control/show.html.twig', array(
            'control' => $control,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Control entity.
     *
     * @Route("/edit/{numberSlug}", name="passport_control_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_INFO_SPECIALIST')")
     */
    public function editAction(Request $request, Control $control)
    {
        if (!$control->isClosed()){
            $user = null;

            if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $user = $this->getUser();  // get $user object
            }

            if ($user){
                $control->setLastUpdateBy($user);
            }

            $deleteForm = $this->createDeleteForm($control);
            $editForm = $this->createForm('DRI\PassportBundle\Form\ControlType', $control, [
                'currentAction' => 'edit'
            ])->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $passRepo = $em->getRepository('DRIPassportBundle:Passport');

                $pass = $passRepo->find($control->getPassport());

                if ($control->getPickUpDate() == null){
                    $pass->setInStore(false);
                }else{
                    $pass->setInStore(true);
                }

                $em->persist($control);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'El Control de Pasaporte se editó satisfactoriamente!');
                return $this->redirectToRoute('passport_control_edit', array('numberSlug' => $control->getNumberSlug()));
            }

            $lastVisited = $request->server->get('HTTP_REFERER');

            return $this->render('@DRIPassport/Control/edit.html.twig', array(
                'lastPage' => $lastVisited,
                'control' => $control,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else{
            $this->get('session')->getFlashBag()->add(
                'error',
                'El Control de Pasaporte está cerrado por lo que no se puede modificar.'
            );

            return $this->redirectToRoute('passport_control_show', array('numberSlug' => $control->getNumberSlug()));
        }
    }

    /**
     * Deletes a Control entity.
     *
     * @Route("/{id}", name="passport_control_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Control $control)
    {
    
        $form = $this->createDeleteForm($control);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($control);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'El Control se borro satisfactorimente!');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Existe un probema para borrar el Control');
        }
        
        return $this->redirectToRoute('passport_control_index');
    }
    
    /**
     * Creates a form to delete a Control entity.
     *
     * @param Control $control The Control entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Control $control)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('passport_control_delete', array('id' => $control->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Control by id
     *
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="passport_control_by_id_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteByIdAction(Control $control){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($control);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'El Control se borro satisfactorimente');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Existe un probema para borrar el Control');
        }

        return $this->redirect($this->generateUrl('passport_application_index'));

    }
    

    /**
    * Bulk Action
    * @Route("/bulk/delete", name="passport_control_bulk_action")
     * @Method({"GET", "POST"})
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
            $repository = $em->getRepository('DRIPassportBundle:Control');

            $counter = 0;

            foreach ($choices as $choice) {
                $entity = $repository->find($choice['id']);
                $em->remove($entity);
                $counter++;
            }

            $em->flush();

            return new Response('Se borraron satisfactoriamente '.$counter.' controles', 200);
        }


        return new Response('Bad Request', 400);
    }
    

}
