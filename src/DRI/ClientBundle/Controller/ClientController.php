<?php

namespace DRI\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Form\FormInterface;

use Exception;

use DRI\UsefulBundle\Useful\Useful;
use DRI\ClientBundle\Entity\Client;


/**
 * Client controller.
 *
 * @package ClientBundle\Controller
 */
class ClientController extends Controller
{
    /**
     * Estatistics for Client entities.
     *
     * @Route("/index", name="client_index")
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('DRIClientBundle:Client:index.html.twig');
    }

    /**
     * Lists all Client entities.
     *
     * @param Request $request
     * @Route("/list", name="client_list", methods={"GET"})
     * @return Response
     * @throws Exception
     */
    public function listAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        $datatable = $this->get('app.datatable.clients.client');
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
     * @Route("/new", name="client_new", methods={"GET","POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST') or has_role('ROLE_INFO_SPECIALIST')")
     * @return RedirectResponse|Response
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

            $passport = $passRepo->findOneBy(['clientCi'],$client);
            if($passport){
                $passport->setHolder($client);
                $em->persist($passport);
            }

            $em->flush();

            return $this->redirectToRoute('client_profile', array('fullNameSlug' => $client->getFullNameSlug()));
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
     * @Route("/profile/{fullNameSlug}", name="client_profile", options = {"expose" = true}, methods={"GET"})
     * @return Response
     */
    public function profileAction(Client $client)
    {
        $deleteForm = $this->createDeleteForm($client);

        return $this->render('@DRIClient/Client/profile.html.twig', array(
            'client' => $client,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to config an existing client entity.
     *
     * @param Request $request
     * @param Client  $client
     * @Route("/config/{fullNameSlug}", name="client_config", options = {"expose" = true}, methods={"GET", "POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST') or has_role('ROLE_INFO_SPECIALIST')")
     *
     * @return RedirectResponse|Response
     */
    public function configAction(Request $request, Client $client)
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

            return $this->redirectToRoute('client_config', array('fullNameSlug' => $client->getFullNameSlug()));
        }

        return $this->render('DRIClientBundle:Client:config.html.twig', array(
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
     * @Route("/{id}", name="client_delete", methods={"DELETE"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return RedirectResponse
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
     * @return FormInterface The form
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
     * @Route("/bulk/delete", name="client_bulk_delete", methods={"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
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
     * @Route("/usernames", name="select2_usernames")
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
     * @param Client $client
     * @Route("/{id}/pdf", name="client_pdf")
     * @return Response
     */
    public function pdfAction(Client $client) {
        $html = $this->renderView('DRIClientBundle::pdf_report.html.twig', [
            'client' => $client
        ]);

        $name = $client->getFullNameSlug();

        $filename = sprintf('%s-%s.pdf', $name, date('Y-m-d'));

        $response = new Response (
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html,
                [
                    'images' => true,
                    'enable-javascript' => true,
                    'margin-left' => '15mm',
                    'margin-right' => '15mm',
                    'margin-top' => '15mm',
                    'margin-bottom' => '15mm',
                    //'orientation' => 'landscape',
                ]),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('inline; filename="%s"', $filename),
                //'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
        return $response;
    }

    /**
     * Available CI.
     *
     * @param Request $request
     * @Route("/ci-available", name="client__ci_is_available", options={"expose"=true}, methods={"GET", "POST"})
     * @return JsonResponse|Response
     */
    public function isAvailableCIAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $ci = $request->request->get('ci');

            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository('DRIClientBundle:Client')->findOneBy(['ci'],$ci);

            if(!$exist){
                return new JsonResponse(true);
            }
        }

        return new JsonResponse(false);

    }

    /**
     * Available Email.
     *
     * @param Request $request
     * @Route("/email-available", name="client__email_is_available", options={"expose"=true}, methods={"GET", "POST"})
     * @return JsonResponse|Response
     */
    public function isAvailableEmailAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $email = $request->request->get('email');

            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository('DRIClientBundle:Client')->findOneBy(['email'],$email);

            if(!$exist){
                return new JsonResponse(true);
            }
        }

        return new JsonResponse(false);

    }

    /**
     * Generate the Short Name for all Client entities
     *
     * @Route("/generate-shortname", name="generate-shortname")
     */
    public function generateShortNameAction()
    {
        $em = $this->getDoctrine()->getManager();
        $clientRepo = $em->getRepository("DRIClientBundle:Client");

        $clients = $clientRepo->findAll();

        foreach ($clients as $client){
            $client->setShortNameSlug(Useful::getSlug($client->getShortName()));

            $em->persist($client);
            $em->flush();
        }
    }

    /**
     * Generate the Short Name for all Client entities
     *
     * @Route("/generate-type", name="generate-type")
     * @throws Exception
     */
    public function generateTypeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $clientRepo = $em->getRepository("DRIClientBundle:Client");

        $clients = $clientRepo->findAll();

        foreach ($clients as $client){
            switch ($client->getClientType()){
                case 'DIR': $client->setClientType('dir');break;
                case 'DOC': $client->setClientType('doc');break;
                case 'NOD': $client->setClientType('nod');break;
                case 'EST': $client->setClientType('est');break;
            }

            $em->persist($client);
        }
            $em->flush();
    }
}
