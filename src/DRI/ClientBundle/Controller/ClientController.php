<?php

namespace DRI\ClientBundle\Controller;

use DRI\ClientBundle\Datatables\ClientDatatable;
use DRI\ClientBundle\Entity\Client;

use PhpParser\Node\Scalar\String_;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


/**
 * Client controller.
 *
 * @package ClientBundle\Controller
 */
class ClientController extends Controller
{
    /**
     * Lists all Client entities.
     *
     * @param Request $request
     *
     * @Route("/index", name="client_index")
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('DRIClientBundle:Client:index.html.twig', array(

        ));
    }

    /**
     * Lists all Client entities.
     *
     * @param Request $request
     *
     * @Route("/list", name="client_list")
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
        $datatable = $this->get('sg_datatables.factory')->create(ClientDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DRIClientBundle:Client:list.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Creates a new client entity.
     *
     * @param Request $request
     *
     * @Route("/new", name="client_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST') or has_role('ROLE_INFO_SPECIALIST')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $passRepo   = $em->getRepository('DRIPassportBundle:Passport');

        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $client = new Client();
        if ($user){
            $client->setCreatedBy($user);
        }

        $form = $this->createForm('DRI\ClientBundle\Form\ClientType', $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($client);

            $passport = $passRepo->findOneByClientCi($client->getCI());
            if($passport){
                $passport->setHolder($client);
                $em->persist($passport);
            }


            $em->flush();

            return $this->redirectToRoute('client_show', array('fullNameSlug' => $client->getFullNameSlug()));
        }

        return $this->render('DRIClientBundle:Client:new.html.twig', array(
            'client' => $client,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a client entity.
     *
     * @param Client $client
     *
     * @Route("/view/{fullNameSlug}", name="client_show", options = {"expose" = true})
     * @Method("GET")
     *
     * @return Response
     */
    public function showAction(Client $client)
    {
        $deleteForm = $this->createDeleteForm($client);

        return $this->render('@DRIClient/Client/show.html.twig', array(
            'client' => $client,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     * @param Request $request
     * @param Client  $client
     *
     * @Route("/edit/{fullNameSlug}", name="client_edit", options = {"expose" = true})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST') or has_role('ROLE_INFO_SPECIALIST')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, Client $client)
    {
        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        if ($user){
            $client->setLastUpdateBy($user);
        }

        $deleteForm = $this->createDeleteForm($client);

        $giForm = $this->createForm('DRI\ClientBundle\Form\ClientGeneralInfoType', $client);
        $giForm->handleRequest($request);

        $dpForm = $this->createForm('DRI\ClientBundle\Form\ClientDataPassportType', $client);
        $dpForm->handleRequest($request);

        $dcForm = $this->createForm('DRI\ClientBundle\Form\ClientDataAtCenterType', $client);
        $dcForm->handleRequest($request);

        $ciForm = $this->createForm('DRI\ClientBundle\Form\ClientChangeImageType', $client);
        $ciForm->handleRequest($request);

        if (($giForm->isSubmitted() && $giForm->isValid())||
            ($ciForm->isSubmitted() && $ciForm->isValid())||
            ($dpForm->isSubmitted() && $dpForm->isValid())||
            ($dcForm->isSubmitted() && $dcForm->isValid())){
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_edit', array('fullNameSlug' => $client->getFullNameSlug()));
        }

        return $this->render('DRIClientBundle:Client:edit.html.twig', array(
            'client' => $client,
            'gi_form' => $giForm->createView(),
            'ci_form' => $ciForm->createView(),
            'dp_form' => $dpForm->createView(),
            'dc_form' => $dcForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a client entity.
     *
     * @param Request $request
     * @param Client  $client
     *
     * @Route("/{id}", name="client_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Client $client)
    {
        $form = $this->createDeleteForm($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($client);
            $em->flush();
        }

        return $this->redirectToRoute('client_index');
    }

    /**
     * Creates a form to delete a client entity.
     *
     * @param Client $client The client entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Client $client)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('client_delete', array('id' => $client->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * Bulk delete action.
     *
     * @param Request $request
     *
     * @Route("/bulk/delete", name="client_bulk_delete")
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
            $repository = $em->getRepository('DRIClientBundle:Client');

            $counter = 0;

            foreach ($choices as $choice) {
                $entity = $repository->find($choice['id']);
                $em->remove($entity);
                $counter++;
            }

            $em->flush();

            return new Response('Se borraron satisfactoriamente '.$counter.' clientes', 200);
        }


        return new Response('Bad Request', 400);
    }

    /**
     * Get all Users from Database to show in Select2-Filter.
     *
     * @param Request $request
     *
     * @Route("/usernames", name="select2_usernames")
     *
     * @return JsonResponse|Response
     */
    public function select2CreatedByUsersnames(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('DRIUserBundle:User')->findAll();

            $result = array();

            foreach ($users as $user) {
                $result[$user->getId()] = $user->getUsername();
            }

            return new JsonResponse($result);
        }

        return new Response('Bad request.', 400);
    }

    /**
     * Generate and save a PDF
     *
     * @Route("/{id}/pdf", name="client_pdf")
     */
    public function pdfAction(Client $client, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $c = $em->getRepository('DRIClientBundle:Client')->find($client);

        $html = $this->renderView('DRIClientBundle::1.htm.twig', [
            'client' => $client
        ]);

        $name = $client->getFullNameSlug();

        $filename = sprintf('%s-%s.pdf', $name, date('Y-m-d'));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }

    /**
     * Available CI.
     *
     * @param Request $request
     *
     * @Route("/ci-available", name="client__ci_is_available", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @return JsonResponse|Response
     */
    public function isAvailableCIAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $ci = $request->request->get('ci');

            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository('DRIClientBundle:Client')->findOneByCi($ci);

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
     * @Route("/email-available", name="client__email_is_available", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @return JsonResponse|Response
     */
    public function isAvailableEmailAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $email = $request->request->get('email');

            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository('DRIClientBundle:Client')->findOneByEmail($email);

            if(!$exist){
                return new JsonResponse(true);
            }
            else {
                return new JsonResponse(false);
            }
        }

    }

    /**
     * @Route("/generate-shortname", name="generate-shortname")
     */
    public function generateShortNameAction()
    {
        $em = $this->getDoctrine()->getManager();
        $clientRepo = $em->getRepository("DRIClientBundle:Client");

        $clients = $clientRepo->findAll();

        foreach ($clients as $client){
            $client->setShortName($client->getFirstName().' '.$client->getFirstLastName());

            $em->persist($client);
            $em->flush();
        }
    }
}
