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

use DRI\ExitBundle\Entity\Departure;
use DRI\ExitBundle\Datatables\DepartureDatatable;
use DRI\ClientBundle\Entity\Client;
use DRI\ExitBundle\Entity\Application;
use DRI\ExitBundle\Form\ApplicationType;
use DRI\ExitBundle\Form\DepartureType;

/**
 * Departure controller.
 *
 * @Route("/departure")
 */
class DepartureController extends Controller
{
    /**
     * Estatistics for Departure entities.
     *
     * @param Request $request
     *
     * @Route("/index", name="departure_index")
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('@DRIExit/Departure/index.html.twig', array(

        ));
    }

    /**
     * Lists all Departure entities.
     *
     * @param Request $request
     *
     * @Route("/list", name="departure_list")
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
        $datatable = $this->get('sg_datatables.factory')->create(DepartureDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('@DRIExit/Departure/list.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Creates a new departure entity.
     *
     * @param Request $request
     * @param Client $clientset
     *
     * @Route("/new/{clientset}", name="departure_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST')")
     *
     * @return Response
     */
    public function newAction(Request $request, $clientset = null)
    {
        $user = null;
        $client = null;
        $app = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $em = $this->getDoctrine()->getManager();
        $clientRepo = $em->getRepository('DRIClientBundle:Client');
        $appRepo = $em->getRepository('DRIExitBundle:Application');

        $departure = new Departure();

        if ($user){
            $departure->setCreatedBy($user);
        }
        if($clientset){

            $client = $clientRepo->find($clientset);

            if(!$client){
                throw $this->createNotFoundException("El cliente al que se le intenta crear la Salida no existe.");
            }

            $departure->setClient($client);
        }

        $form = $this->createForm('DRI\ExitBundle\Form\DepartureType', $departure, [
            'currentAction' => 'new'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!($departure->getApplication() == null)){
                $app = $appRepo->find($departure->getApplication());

                if($app){
                    $app->setUsed(true);
                }
            }

            $em->persist($departure);
            $em->flush();

            $editLink = $this->generateUrl('departure_edit', array('numberSlug' => $departure->getNumberSlug()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se creó una nueva Salida al Exterior.</a>" );

            return $this->redirectToRoute('departure_show', array('numberSlug' => $departure->getNumberSlug()));
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('DRIExitBundle:Departure:new.html.twig', array(
            'lastPage' => $lastVisited,
            'client' => $client,
            'application' => $app,
            'departure' => $departure,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Passport from Application entity.
     *
     * @param Request $request
     * @param Application $application
     *
     * @Route("/new-from-application/{application}", name="departure_new_from_application")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST')")
     *
     * @return Response
     */
    public function newFromApplicationAction(Request $request, $application = null)
    {
        $user = null;
        $client = null;
        $app    = null;
        $lastVisited = $request->server->get('HTTP_REFERER');

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $em = $this->getDoctrine()->getManager();
        $clientRepo = $em->getRepository('DRIClientBundle:Client');
        $applicationRepo = $em->getRepository('DRIExitBundle:Application');

        $departure = new Departure();

        if ($user){
            $departure->setCreatedBy($user);
        }
        if($application){

            $app    = $applicationRepo->find($application);
            if(!$app){
                throw $this->createNotFoundException("El cliente al que se le intenta crear la salida no tiene Solicitudes.");
            }

            $client = $clientRepo->find($app->getClient());
            if(!$client){
                throw $this->createNotFoundException("El cliente al que se le intenta crear la salida no existe.");
            }

            $departure->setClient($client);
            $departure->setApplication($app);
        }

        $form = $this->createForm(DepartureType::class, $departure, [
            'currentAction' => 'new'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $app->setUsed(true);

            $em->persist($departure);
            $em->flush();

            $editLink = $this->generateUrl('departure_edit', array('numberSlug' => $departure->getNumberSlug()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se creó una nueva Solicitud de Pasaporte.</a>" );

            return $this->redirectToRoute('departure_show', array('numberSlug' => $departure->getNumberSlug()));
        }

        return $this->render('@DRIExit/Departure/new.html.twig', array(
            'lastPage' => $lastVisited,
            'client' => $client,
            'application' => $app,
            'Passport' => $departure,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a departure entity.
     *
     * @param Departure $departure
     *
     * @Route("/{numberSlug}", name="departure_show")
     * @Method("GET")
     *
     * @return Response
     */
    public function showAction(Departure $departure)
    {
        $deleteForm = $this->createDeleteForm($departure);

        return $this->render('DRIExitBundle:Departure:show.html.twig', array(
            'departure' => $departure,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Departure entity.
     *
     * @param Request $request
     * @param Departure $departure
     *
     * @Route("/edit/{numberSlug}", name="departure_edit", options = {"expose" = true})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_REQUIRE_SPECIALIST')")
     *
     * @return Response
     */
    public function editAction(Request $request, Departure $departure)
    {
        if (!$departure->isClosed()) {
            $user = null;

            if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $user = $this->getUser();  // get $user object
            }

            if ($user){
                $departure->setLastUpdateBy($user);
            }

            $deleteForm = $this->createDeleteForm($departure);
            $editForm = $this->createForm('DRI\ExitBundle\Form\DepartureType', $departure, [
                'currentAction' => 'edit'
            ])->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $appRepo = $em->getRepository('DRIExitBundle:Application');

                if (!($departure->getApplication() == null)){
                    $app = $appRepo->find($departure->getApplication());

                    if($app){
                        $app->setUsed(true);
                    }
                }

                $em->persist($departure);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'La Salida se editó satisfactoriamente!');
                return $this->redirectToRoute('departure_show', array('numberSlug' => $departure->getNumberSlug()));
            }

            $lastVisited = $request->server->get('HTTP_REFERER');

            return $this->render('DRIExitBundle:Departure:edit.html.twig', array(
                'lastPage' => $lastVisited,
                'departure' => $departure,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else{
            $this->get('session')->getFlashBag()->add(
                'error',
                'La Salida esta cerrada por lo que no se puede modificar.'
            );

            return $this->redirectToRoute('exit_application_show', array('numberSlug' => $departure->getNumberSlug()));
        }
    }

    /**
     * Deletes a Departure entity.
     *
     * @Route("/{id}", name="departure_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Departure $departure)
    {

        $form = $this->createDeleteForm($departure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($departure);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'La Salida fue eliminada satisfactoriamente');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Existe un problema para eliminar la Salida');
        }

        return $this->redirectToRoute('departure_list');
    }

    /**
     * Creates a form to delete a Departure entity.
     *
     * @param Departure $departure The Departure entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Departure $departure)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('departure_delete', array('id' => $departure->getId())))
            ->setMethod('POST')
            ->getForm()
            ;
    }

    /**
     * Delete Departure by id
     *
     * @param Departure $departure
     *
     * @Route("/delete/{id}", name="departure_by_id_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function deleteByIdAction(Departure $departure){
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($departure);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'La Salida fue eliminada satisfactoriamente');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Existe un problema para eliminar la Salida');
        }

        return $this->redirect($this->generateUrl('departure_list'));

    }

    /**
     * Bulk delete action.
     *
     * @param Request $request
     *
     * @Route("/bulk/delete", name="departure_bulk_delete")
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
            $repository = $em->getRepository('DRIExitBundle:Departure');

            $counter = 0;

            foreach ($choices as $choice) {
                $entity = $repository->find($choice['id']);
                $em->remove($entity);
                $counter++;
            }

            $em->flush();

            return new Response('Se borraron satisfactoriamente '.$counter.' salidas', 200);
        }


        return new Response('Bad Request', 400);
    }

    /**
     * Returns a JSON string with the dependencies of the Client with the providen id.
     *
     * @param Request $request
     *
     * @Route("/list_client_dependencies", name="list_client_dependencies", options={"expose"=true})
     * @Method({"POST"})
     *
     * @return JsonResponse|Response
     */
    public function listClientDependenciesAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $client = $request->request->get('client');
            $client = (int)$client;

            if(!$client){
                throw $this->createNotFoundException("No llego el cliente.");
            }

            $em = $this->getDoctrine()->getManager();
            $applicationsRepository = $em->getRepository("DRIExitBundle:Application");
            $passportsRepository = $em->getRepository("DRIPassportBundle:Passport");

            // Search the applications that belongs to the client with the given id as GET parameter "clientid"
            $applications = $applicationsRepository->createQueryBuilder("q")
                ->where("q.client = :client")
                ->andWhere("q.state = :state")
                ->andWhere("q.used = :used")
                ->setParameter("client", $client)
                ->setParameter("state", "APR")
                ->setParameter("used", false)
                ->getQuery()
                ->getResult();

            // Search the passports that belongs to the client with the given id as GET parameter "clientid"
            $passports = $passportsRepository->createQueryBuilder("q")
                ->where("q.holder = :client")
                ->andWhere("q.drop = :drop")
                ->setParameter("client", $client)
                ->setParameter("drop", false)
                ->getQuery()
                ->getResult();

            // Serialize into an array the data that we need, in this case only number and id
            // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
            $applicationsList = array();
            $passportsList = array();

            foreach ($applications as $application) {
                $applicationsList[] = array(
                    "id" => $application->getId(),
                    "number" => $application->getNumber()
                );
            }

            foreach ($passports as $passport) {
                $passportsList[] = array(
                    "id" => $passport->getId(),
                    "number" => $passport->getNumber()
                );
            }

            // Return array with structure of the passports of the providen client id
            return new JsonResponse(
                array(
                    'applications'  => $applicationsList,
                    'passports'     => $passportsList
                )
            );
        }
    }
}
