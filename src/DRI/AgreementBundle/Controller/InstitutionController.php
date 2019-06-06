<?php

namespace DRI\AgreementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Form\FormInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use DRI\AgreementBundle\Entity\Institution;

/**
 * Institution controller.
 *
 * @Route("/institution")
 */
class InstitutionController extends Controller
{
    /**
     * Lists all Institutions entities.
     *
     * @param Request $request
     * @Route("/index", name="institution_index", methods={"GET"})
     * @return Response
     * @throws \Exception
     */
    public function listAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('app.datatable.agreement.institution');
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
     * Displays a form to create a new Institution entity.
     *
     * @param Request $request
     * @Route("/new", name="institution_new", methods={"GET", "POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST') or has_role('ROLE_MANAGE_SPECIALIST')")
     * @return Response
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
     * @param Institution $institution
     * @Route("/{id}", name="institution_show", options = {"expose" = true}, methods={"GET"})
     * @return Response
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
     * @param Request $request
     * @param Institution $institution
     * @Route("/edit/{id}", name="institution_edit", options = {"expose" = true}, methods={"GET", "POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST') or has_role('ROLE_MANAGE_SPECIALIST')")
     * @return Response
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
     * @param Request $request
     * @param Institution $institution
     * @Route("/{id}", name="institution_delete", methods={"DELETE"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
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
     * @return FormInterface The form
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
     * @param Institution $institution The entity id
     * @Route("/delete/{id}", name="institution_by_id_delete", methods={"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
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
     *
     * @param Request $request
     * @Route("/bulk-action/", name="institution_bulk_delete", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
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
