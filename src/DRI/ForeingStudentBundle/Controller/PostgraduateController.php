<?php

namespace DRI\ForeingStudentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Exception;

use DRI\ForeingStudentBundle\Entity\Postgraduate;

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
     * @Route("/", name="postgraduate_index", methods={"GET"})
     * @return Response
     */
    public function indexAction()
    {
        return new Response('Sin Implementar');
    }

    /**
     * Lists all Postgraduate entities.
     *
     * @param Request $request
     * @Route("/list", name="postgraduate_list", methods={"GET"})
     * @return Response
     * @throws Exception
     */
    public function listAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('app.datatable.foreingstudents.posgraduate');
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
     * Displays a form to create a new Postgraduate entity.
     *
     * @param Request $request
     * @Route("/new", name="postgraduate_new", methods={"GET", "POST"})
     * @Security("has_role('ROLE_FS_SPECIALIST')")
     * @return Response
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
     * @param Postgraduate $postgraduate
     * @Route("/profile/{fullNameSlug}", name="postgraduate_show", options = {"expose" = true}, methods={"GET"})
     * @return Response
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
     * @param Request $request
     * @param Postgraduate $postgraduate
     * @Route("/edit/{fullNameSlug}", name="postgraduate_edit", options = {"expose" = true}, methods={"GET", "POST"})
     * @Security("has_role('ROLE_FS_SPECIALIST')")
     * @return Response
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
     * @param Request $request
     * @param Postgraduate $postgraduate
     * @Route("/{id}", name="postgraduate_delete", methods={"DELETE"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
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
     * @return FormInterface The form
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
     * @param Postgraduate $postgraduate
     * @Route("/delete/{id}", name="postgraduate_by_id_delete", methods={"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
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
     *
     * @param Request $request
     * @Route("/bulk-action/", name="postgraduate_bulk_delete", methods={"POST"})
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
     * @Route("/ci-available", name="postgraduate_ci_is_available", options={"expose"=true}, methods={"GET", "POST"})
     * @return JsonResponse|Response
     */
    public function isAvailableCIAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $ci = $request->request->get('ci');

            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository('DRIForeingStudentBundle:Postgraduate')->findOneBy(['ci'],$ci);

            if(!$exist){
                return new JsonResponse(true);
            }
            else {
                return new JsonResponse(false);
            }
        }

        return new Response('Solicitud Incorrecta', Response::HTTP_BAD_REQUEST);

    }

    /**
     * Available Email.
     *
     * @param Request $request
     * @Route("/email-available", name="postgraduate_email_is_available", options={"expose"=true}, methods={"GET", "POST"})
     * @return JsonResponse|Response
     */
    public function isAvailableEmailAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $email = $request->request->get('email');

            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository('DRIForeingStudentBundle:Postgraduate')->findOneBy(['email'],$email);

            if(!$exist){
                return new JsonResponse(true);
            }
            else {
                return new JsonResponse(false);
            }
        }

        return new Response('Solicitud Incorrecta', Response::HTTP_BAD_REQUEST);

    }

    /**
     * Returns a JSON string with the dependencies of the CourseType.
     *
     * @param Request $request
     * @Route("/list-postgraduate-courses-dependencies", name="list_postgraduate_courses_dependencies", options={"expose"=true}, methods={"POST"})
     * @return JsonResponse|Response
     */
    public function listCourseDependenciesAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();
        $em = $this->getDoctrine()->getManager();
        $courseRepo = $em->getRepository("DRIUsefulBundle:Course");

        if ($isAjax) {
            $courseType = $request->request->get('type');

            $courses = $courseRepo->findBy(['type'],$courseType);

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

        return new Response('Solicitud Incorrecta', Response::HTTP_BAD_REQUEST);
    }
}
