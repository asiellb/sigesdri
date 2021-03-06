<?php

namespace DRI\PassportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use DRI\PassportBundle\Entity\Passport;
use DRI\PassportBundle\Form\PassportType;
use DRI\UsefulBundle\Useful\Useful;

/**
 * Passport controller.
 */
class PassportController extends Controller
{
    /**
     * Estatistics for Passport entities.
     *
     * @Route("/index", name="passport_index")
     * @return Response
     */
    public function indexAction()
    {
        $chartData = $this->passportsChart();

        return $this->render('DRIPassportBundle:Passport:index.html.twig', array(
            'chartData'     => $chartData,
        ));
    }

    /**
     * Lists all Application entities.
     *
     * @param Request $request
     * @Route("/list", name="passport_list", methods={"GET"})
     * @return JsonResponse|Response
     * @throws \Exception
     */
    public function listAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('app.datatable.passports.passport');
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DRIPassportBundle:Passport:list.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Creates a new Passport entity.
     *
     * @param Request $request
     * @param null $client
     * @Route("/new/{client}", name="passport_new", methods={"GET", "POST"})
     * @Security("has_role('ROLE_INFO_SPECIALIST')")
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request, $client = null)
    {
        $user = null;
        $holder = null;
        $app = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $em = $this->getDoctrine()->getManager();
        $clientRepo = $em->getRepository('DRIClientBundle:Client');
        $appRepo = $em->getRepository('DRIPassportBundle:Application');

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

        $form = $this->createForm(PassportType::class, $passport, [
            'currentAction' => 'new'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$client && !($passport->getHolder() == null)){
                $holder = $clientRepo->find($passport->getHolder());

                if(!$holder){
                    throw $this->createNotFoundException("El cliente al que se le intenta crear el pasaporte no existe.");
                }
                $passport->setClientCi($holder->getCi());
            }

            if (!$client && ($passport->getHolder() == null) && !($passport->getClientCi() == null)){
                $holder = $clientRepo->findOneBy(['ci'],$passport->getClientCi());

                if($holder){
                    $passport->setHolder($holder);
                }
            }

            if (!($passport->getApplication() == null)){
                $app = $appRepo->find($passport->getApplication());

                if($app){
                    $app->setUsed(true);
                }
            }

            $em->persist($passport);
            $em->flush();

            $editLink = $this->generateUrl('passport_edit', array('numberSlug' => $passport->getNumberSlug()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se creó un nuevo Pasaporte.</a>" );

            return $this->redirectToRoute('passport_show', array('numberSlug' => $passport->getNumberSlug()));
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('@DRIPassport/Passport/new.html.twig', array(
            'lastPage' => $lastVisited,
            'holder' => $holder,
            'application' => $app,
            'Passport' => $passport,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Passport from Application entity.
     *
     * @param Request $request
     * @param null $application
     * @Route("/new-from-application/{application}", name="passport_new_from_application", methods={"GET", "POST"})
     * @Security("has_role('ROLE_INFO_SPECIALIST')")
     * @return RedirectResponse|Response
     */
    public function newFromApplicationAction(Request $request, $application = null)
    {
        $user = null;
        $holder = null;
        $app    = null;
        $lastVisited = $request->server->get('HTTP_REFERER');

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $em = $this->getDoctrine()->getManager();
        $clientRepo = $em->getRepository('DRIClientBundle:Client');
        $applicationRepo = $em->getRepository('DRIPassportBundle:Application');

        $passport = new Passport();

        if ($user){
            $passport->setCreatedBy($user);
        }
        if($application){

            $app    = $applicationRepo->find($application);
            if(!$app){
                throw $this->createNotFoundException("El cliente al que se le intenta crear el pasaporte no tiene Solicitudes.");
            }

            $holder = $clientRepo->find($app->getClient());
            if(!$holder){
                throw $this->createNotFoundException("El cliente al que se le intenta crear el pasaporte no existe.");
            }

            $passport->setHolder($holder);
            $passport->setClientCi($holder->getCi());
            $passport->setApplication($app);
            $passport->setType($app->getPassportType());
        }

        $form = $this->createForm(PassportType::class, $passport, [
            'currentAction' => 'new'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $app->setUsed(true);

            $em->persist($passport);
            $em->flush();

            $editLink = $this->generateUrl('passport_edit', array('numberSlug' => $passport->getNumberSlug()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se creó un nuevo Pasaporte.</a>" );

            return $this->redirectToRoute('passport_show', array('numberSlug' => $passport->getNumberSlug()));
        }


        return $this->render('@DRIPassport/Passport/new.html.twig', array(
            'lastPage' => $lastVisited,
            'holder' => $holder,
            'application' => $app,
            'Passport' => $passport,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Passport entity.
     *
     * @param Passport $passport
     * @Route("/view/{numberSlug}", name="passport_show", options = {"expose" = true}, methods={"GET"})
     * @return Response
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
     * @param Request $request
     * @param Passport  $passport
     * @Route("/edit/{numberSlug}", name="passport_edit", options = {"expose" = true}, methods={"GET", "POST"})
     * @Security("has_role('ROLE_INFO_SPECIALIST')")
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Passport $passport)
    {
        if (!$passport->isClosed()){
            $user = null;

            if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $user = $this->getUser();  // get $user object
            }

            if ($user){
                $passport->setLastUpdateBy($user);
            }

            $deleteForm = $this->createDeleteForm($passport);
            $editForm = $this->createForm('DRI\PassportBundle\Form\PassportType', $passport, [
                'currentAction' => 'edit'
            ])->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $appRepo = $em->getRepository('DRIPassportBundle:Application');

                if (!($passport->getApplication() == null)){
                    $app = $appRepo->find($passport->getApplication());

                    if($app){
                        $app->setUsed(true);
                    }
                }


                $em->persist($passport);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'El Pasaporte se editó satisfactoriamente!');
                return $this->redirectToRoute('passport_show', array('numberSlug' => $passport->getNumberSlug()));
            }

            $lastVisited = $request->server->get('HTTP_REFERER');


            return $this->render('@DRIPassport/Passport/edit.html.twig', array(
                'lastPage' => $lastVisited,
                'passport' => $passport,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else{
            $this->get('session')->getFlashBag()->add(
                'error',
                'El Pasaporte esta cerrado por lo que no se puede modificar.'
            );

            return $this->redirectToRoute('passport_show', array('numberSlug' => $passport->getNumberSlug()));
        }

    }

    /**
     * Deletes a Passport entity.
     *
     * @param Request $request
     * @param Passport $passport
     * @Route("/delete/{id}", name="passport_delete", methods={"DELETE"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return RedirectResponse
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

        return $this->redirectToRoute('passport_list');
    }

    /**
     * Creates a form to delete a Passport entity.
     *
     * @param Passport $passport The Passport entity
     * @return FormInterface The form
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
     * @param Passport $passport The entity id
     * @Route("/delete-by-id/{id}", name="passport_by_id_delete", methods={"GET"})
     * @Security("has_role('ROLE_ADMIN')")
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

        return $this->redirect($this->generateUrl('passport_list'));

    }

    /**
     * Bulk delete action.
     *
     * @param Request $request
     * @Route("/bulk/delete", name="passport_bulk_delete", methods={"GET", "POST"})
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
     * Assign Client entity for Passport entity.
     *
     * @Route("/assign_passport")
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     */
    public function assignPassport(){

        $em = $this->getDoctrine()->getManager();
        $clientRepo = $em->getRepository('DRIClientBundle:Client');
        $passRepo   = $em->getRepository('DRIPassportBundle:Passport');

        $clients = $clientRepo->findAll();

        foreach ($clients as $client){
            if ($passRepo->findOneBy(['clientCi'],$client->getCI())){

                $passport = $passRepo->findOneBy(['clientCi'],$client->getCI());

                $passport->setHolder($client);

                $em->persist($passport);
                $em->flush();

            }
        }

        return new Response('Asignando Pasaporte a los Clientes.');
    }

    /**
     * Assign numberSlug for Passport entity.
     *
     * @Route("/assign_number_slug")
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     */
    public function assignNumberSlug(){

        $em = $this->getDoctrine()->getManager();
        $passRepo   = $em->getRepository('DRIPassportBundle:Passport');

        $passports = $passRepo->findBy(['numberSlug'],['']);

        foreach ($passports as $passport){
            $passport->setNumberSlug(Useful::getSlug($passport->getNumber()));

            $em->persist($passport);
            $em->flush();
        }

        return new Response('Asignando NumberSlug a los Pasaportes.');
    }

    /**
     * Assign numberSlug for Passport entity.
     *
     * @Route("/assign_drop_false")
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     */
    public function assignDropFalse(){

        $em = $this->getDoctrine()->getManager();
        $passRepo   = $em->getRepository('DRIPassportBundle:Passport');

        $passports = $passRepo->findBy(['inStore'],null);

        foreach ($passports as $passport){
            $passport->setInStore(false);

            $em->persist($passport);
            $em->flush();
        }

        return new Response('Asignando Almacenado.');
    }

    /**
     * Cancel the Passport action.
     *
     * @param Request $request
     * @Route("/cancel", name="passport_cancel")
     * @return RedirectResponse
     *
     */
    public function cancelPassport(Request $request){
        $lastVisited = $request->server->get('HTTP_REFERER');
        return new RedirectResponse($lastVisited, 302);
    }

    /**
     * Bulk delete action.
     *
     * @Route("/asig-drop")
     */
    public function asignDropPassport(){
        $em = $this->getDoctrine()->getManager();
        $pass = $em->getRepository('DRIPassportBundle:Passport')->findAll();

        foreach ($pass as $passport){
            $passport->setDrop(false);
        }

        $em->flush();
    }

    /**
     * Available PassNumber.
     *
     * @param Request $request
     * @Route("/pass-available", name="passport__number_is_available", options={"expose"=true}, methods={"GET", "POST"})
     * @return JsonResponse|Response
     */
    public function isAvailableNumberAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $number = $request->request->get('number');

            $em = $this->getDoctrine()->getManager();
            $exist = $em->getRepository('DRIPassportBundle:Passport')->findOneBy(['number'],$number);

            if(!$exist){
                return new JsonResponse(true);
            }
            else {
                return new JsonResponse(false);
            }
        }

        return new Response('Solicitud Incompleta', Response::HTTP_BAD_REQUEST);
    }

    /**
     * Generate Application List.
     *
     * @param Request $request
     * @Route("/list_applications", name="list_client_applications", options={"expose"=true}, methods={"POST"})
     * @return JsonResponse|Response
     */
    public function listApplicationsOfClientAction(Request $request){
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $holder = $request->request->get('holder');
            $holder = (int)$holder;

            $em = $this->getDoctrine()->getManager();
            $appRepository = $em->getRepository("DRIPassportBundle:Application");

            // Search the applications that belongs to the client with the given id as GET parameter "clientid"
            /*$applications = $appRepository->createQueryBuilder("q")
                ->where("q.client = :holder")
                ->andWhere("q.state = :state")
                ->andWhere("q.used = :used")
                ->setParameter("holder", $holder)
                ->setParameter("state", "CNF")
                ->setParameter("used", false)
                ->getQuery()
                ->getResult();*/

            $applications = $appRepository->findBy(['client', 'state', 'used'],[$holder, 'CNF', false]);

            $applicationsList = array();

            foreach($applications as $application){
                $applicationsList[] = array(
                    "id" => $application->getId(),
                    "number" => $application->getNumber()
                );
            }

            // Return array with structure of the passports of the providen client id
            return new JsonResponse(
                array(
                    'applications'  => $applicationsList
                )
            );
        }

        return new Response('Solicitud Incompleta', Response::HTTP_BAD_REQUEST);
    }

    /**
     * Create a Passport Chart
     * @return array
     */
    public function passportsChart(){
        $em = $this->getDoctrine()->getManager();
        $passportRepo = $em->getRepository('DRIPassportBundle:Passport');

        $oficiales  = $passportRepo->findBy(['type'], ['OFI']);
        $ofiActivos = [];
        $ofiVencidos = [];
        $ofiPorVencer = [];

        foreach ($oficiales as $item){
            if ($item->isActive())
                $ofiActivos[] = $item;
            if ($item->isExpired())
                $ofiVencidos[] = $item;
            if ($item->isForExpiring())
                $ofiPorVencer[] = $item;
        }

        $ordinarios = $passportRepo->findBy(['type'], ['COR']);
        $ordActivos = [];
        $ordVencidos = [];
        $ordPorVencer = [];

        foreach ($ordinarios as $item){
            if ($item->isActive())
                $ordActivos[] = $item;
            if ($item->isExpired())
                $ordVencidos[] = $item;
            if ($item->isForExpiring())
                $ordPorVencer[] = $item;
        }

        $chartData = [
            [
                'tipo' => 'Oficiales',
                'cantidad' => count($oficiales),
                'activos' => count($ofiActivos),
                'vencidos' => count($ofiVencidos),
                'porvencer' => count($ofiPorVencer),
            ],[
                'tipo' => 'Ordinarios',
                'cantidad' => count($ordinarios),
                'activos' => count($ordActivos),
                'vencidos' => count($ordVencidos),
                'porvencer' => count($ordPorVencer),
            ]
        ];

        return $chartData;
    }

}
