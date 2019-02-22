<?php

namespace DRI\ExitBundle\Controller;

use DRI\ExitBundle\Entity\ExitApplication;
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
use DRI\ExitBundle\Form\DepartureType;

/**
 * Departure controller.
 *
 * @Route("departure")
 */
class DepartureController extends Controller
{
    /**
     * Lists all Departure entities.
     * @param Request $request
     *
     * @Route("/index", name="departure_index")
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
        $datatable = $this->get('sg_datatables.factory')->create(DepartureDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DRIExitBundle:Departure:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Creates a new departure entity.
     *
     * @Route("/new/{clientset}", name="departure_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_INFO_SPECIALIST')")
     */
    public function newAction(Request $request, $clientset = null)
    {
        $user = null;
        $client = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $em = $this->getDoctrine()->getManager();
        $clientRepo = $em->getRepository('DRIClientBundle:Client');
        $appRepo = $em->getRepository('DRIExitBundle:ExitApplication');

        $departure = new Departure();

        if ($user){
            $departure->setCreatedBy($user);
        }
        if($clientset){

            $client = $clientRepo->find($clientset);
            $applications = $appRepo->findByClient($client);

            if(!$client){
                throw $this->createNotFoundException("El cliente al que se le intenta crear la salida no existe.");
            }

            $departure->setClient($client);

            $form = $this->createForm('DRI\ExitBundle\Form\DepartureType', $departure, [
                'applications' => $applications
            ]);
            $form->handleRequest($request);
        }else{
            $form = $this->createForm('DRI\ExitBundle\Form\DepartureType', $departure);
            $form->handleRequest($request);
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($departure);
            $em->flush();

            return $this->redirectToRoute('departure_show', array('id' => $departure->getId()));
        }

        return $this->render('DRIExitBundle:Departure:new.html.twig', array(
            'departure' => $departure,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a departure entity.
     *
     * @Route("/{id}", name="departure_show")
     * @Method("GET")
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
     * Displays a form to edit an existing departure entity.
     *
     * @Route("/{id}/edit", name="departure_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Departure $departure)
    {
        $deleteForm = $this->createDeleteForm($departure);
        $editForm = $this->createForm('DRI\ExitBundle\Form\DepartureType', $departure);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('departure_edit', array('id' => $departure->getId()));
        }

        return $this->render('DRIExitBundle:Departure:edit.html.twig', array(
            'departure' => $departure,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a Departure entity.
     *
     * @Route("/{id}", name="departure_delete")
     * @Method("DELETE")
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

        return $this->redirectToRoute('departure_index');
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
     * @param mixed $id The entity id
     * @Route("/delete/{id}", name="departure_by_id_delete")
     * @Method("GET")
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

        return $this->redirect($this->generateUrl('departure_index'));

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
     * Returns a JSON string with the passports of the Client with the providen id.
     *
     * @param Request $request
     *
     * @Route("/", name="list_passports_client")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listPassportsOfClientAction(Request $request)
    {
        // Get Entity manager and repository
        $em = $this->getDoctrine()->getManager();
        $passportsRepository    = $em->getRepository("DRIPassportBundle:Passport");
        $applicationsRepository = $em->getRepository("DRIExitBundle:ExitApplication");

        $client_id = $request->query->get('clientid');

        $client_id = (int)$client_id;

        // Search the applications that belongs to the client with the given id as GET parameter "clientid"
        $applications = $applicationsRepository->createQueryBuilder("q")
            ->where("q.client = :client_id")
            ->andWhere("q.state = :state")
            ->setParameter("client_id", $client_id)
            ->setParameter("state", "APR")
            ->getQuery()
            ->getResult();

        // Search the passports that belongs to the client with the given id as GET parameter "clientid"
        $passports = $passportsRepository->createQueryBuilder("q")
            ->where("q.holder = :client_id")
            ->setParameter("client_id", $client_id)
            ->getQuery()
            ->getResult();

        // Serialize into an array the data that we need, in this case only number and id
        // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
        $applicationsList   = array();
        $passportsList      = array();

        foreach($applications as $application){
            $applicationsList[] = array(
                "id" => $application->getId(),
                "number" => $application->getNumber()
            );
        }

        foreach($passports as $passport){
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

        // e.g
        // [{"id":"3","number":"E252974"},{"id":"4","name":"E387290"}]
    }
}
