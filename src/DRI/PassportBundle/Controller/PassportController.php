<?php

namespace DRI\PassportBundle\Controller;

use DRI\PassportBundle\Form\PassportType;
use Elastica\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use DRI\PassportBundle\Entity\Passport;
use DRI\PassportBundle\Datatables\PassportDatatable;

use PhpParser\Node\Scalar\String_;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Passport controller.
 *
 *
 */
class PassportController extends Controller
{
    /**
     * Lists all Application entities.
     * @param Request $request
     *
     * @Route("/index", name="passport_dt_index")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexDTAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.client');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(PassportDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DRIPassportBundle:Passport:index_dt.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Creates a new Passport entity.
     *
     * @Route("/new/{client}", name="passport_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_INFO_SPECIALIST')")
     */
    public function newAction(Request $request, $client = null)
    {
        $user = null;
        $holder = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $em = $this->getDoctrine()->getManager();
        $clientRepo = $em->getRepository('DRIClientBundle:Client');

        $passport = new Passport();

        if ($user){
            $passport->setCreatedBy($user);
        }
        if($client){

            $holder = $clientRepo->find($client);

            if(!$holder){
                throw $this->createNotFoundException("El cliente al que se le intenta crear el pasaporte no existe.");
            }
            $passport->setHolder($holder);
            $passport->setClientCi($holder->getCi());
        }

        $form = $this->createForm(PassportType::class, $passport)
                    ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($passport);
            $em->flush();

            return $this->redirectToRoute('passport_show', array('id' => $passport->getId()));
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('@DRIPassport/Passport/new.html.twig', array(
            'lastPage' => $lastVisited,
            'holder' => $holder,
            'Passport' => $passport,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Passport entity.
     *
     * @Route("/pass/{id}", name="passport_show")
     * @Method("GET")
     */
    public function showAction(Passport $passport)
    {
        $deleteForm = $this->createDeleteForm($passport);

        return $this->render('@DRIPassport/Passport/show.html.twig', array(
            'passport' => $passport,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Passport entity.
     *
     * @Route("/edit/{id}", name="passport_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_INFO_SPECIALIST')")
     */
    public function editAction(Request $request, Passport $passport)
    {
        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        if ($user){
            $passport->setLastUpdateBy($user);
        }

        $deleteForm = $this->createDeleteForm($passport);
        $editForm = $this->createForm('DRI\PassportBundle\Form\PassportType', $passport);
        $editForm->remove('holder');
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($passport);
            $em->flush();

            return $this->redirectToRoute('passport_show', array('id' => $passport->getId()));
        }

        return $this->render('@DRIPassport/Passport/edit.html.twig', array(
            'passport' => $passport,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Passport entity.
     *
     * @Route("/delete/{id}", name="passport_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Passport $passport)
    {
        $form = $this->createDeleteForm($passport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($passport);
            $em->flush();
        }

        return $this->redirectToRoute('_index');
    }

    /**
     * Creates a form to delete a Passport entity.
     *
     * @param Passport $passport The Passport entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Passport $passport)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('passport_delete', array('id' => $passport->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Delete Passport by id
     *
     * @param mixed $id The entity id
     *
     * @Route("/delete-by-id/{id}", name="passport_by_id_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function deleteByIdAction(Passport $passport){
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($passport);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'El pasaporte se borró satisfactoriamente.');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Exite un problema para borrar el pasaporte');
        }

        return $this->redirect($this->generateUrl('passport_dt_index'));

    }

    /**
     * Bulk delete action.
     *
     * @param Request $request
     *
     * @Route("/bulk/delete", name="passport_bulk_delete")
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
            $repository = $em->getRepository('DRIPassportBundle:Passport');

            $counter = 0;

            foreach ($choices as $choice) {
                $entity = $repository->find($choice['id']);
                $em->remove($entity);
                $counter++;
            }

            $em->flush();

            return new Response('Se borraron satisfactoriamente '.$counter.' pasaportes', 200);
        }


        return new Response('Solicitud incorrecta', 400);
    }

    /**
     * Bulk delete action.
     *
     * @Route("/apk")
     * @Security("has_role('ROLE_ADMIN')")
     *
     */
    public function assignPassport(){

        $em = $this->getDoctrine()->getManager();
        $clientRepo = $em->getRepository('DRIClientBundle:Client');
        $passRepo   = $em->getRepository('DRIPassportBundle:Passport');

        $clients = $clientRepo->findAll();

        foreach ($clients as $client){
            if ($passRepo->findOneByClientCi($client->getCI())){

                $passport = $passRepo->findOneByClientCi($client->getCI());

                $passport->setHolder($client);

                $em->persist($passport);
                $em->flush();

            }
        }
        $dondeEstaba = $this->getRequest()->server->get('HTTP_REFERER');
    }

    /**
     * Bulk delete action.
     *
     * @Route("/cancel", name="passport_cancel")
     *
     */
    public function cancelPassport(Request $request){

        $lastVisited = $request->server->get('HTTP_REFERER');
        return new RedirectResponse($lastVisited, 302);
    }


    /**
     * Available PassNumber.
     *
     * @param Request $request
     *
     * @Route("/pass-available", name="passport__number_is_available", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @return JsonResponse|Response
     */
    public function isAvailableNumberAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $number = $request->request->get('number');

            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository('DRIPassportBundle:Passport')->findOneByNumber($number);

            if(!$exist){
                return new JsonResponse(true);
            }
            else {
                return new JsonResponse(false);
            }
        }

    }
}
