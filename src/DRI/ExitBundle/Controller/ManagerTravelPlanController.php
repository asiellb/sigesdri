<?php

namespace DRI\ExitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Doctrine\Common\Collections\ArrayCollection;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrap3View;

use Exception;

use DRI\ExitBundle\Entity\ManagerTravelPlan;



/**
 * ManagerTravelPlan controller.
 *
 * @Route("/managertravelplan")
 */
class ManagerTravelPlanController extends Controller
{
    /**
     * Lists all ManagerTravelPlan entities.
     *
     * @param Request $request
     * @Route("/index", name="exit_managertravelplan_index", methods={"GET"})
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('DRIExitBundle:ManagerTravelPlan')->createQueryBuilder('e');

        list($managerTravelPlans, $pagerHtml) = $this->paginator($queryBuilder, $request);
        
        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('DRIExitBundle:ManagerTravelPlan:index.html.twig', array(
            'managerTravelPlans' => $managerTravelPlans,
            'pagerHtml' => $pagerHtml,
            'totalOfRecordsString' => $totalOfRecordsString
        ));
    }

    /**
     * Show the Manager Travel Plan for the next year.
     *
     * @Route("/current-plan", name="exit_managertravelplan_current_plan", methods={"GET"})
     * @return Response
     */
    public function currentPlanAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entries = $em->getRepository('DRIExitBundle:ManagerTravelPlan')->getCurrentPlan();

        $year = date_format(date_create(), 'Y');

        return $this->render('DRIExitBundle:ManagerTravelPlan:current-plan.html.twig', array(
            'year' => $year,
            'entries' => $entries
        ));
    }

    /**
     * Show the Manager Travel Plan for pass year.
     *
     * @param Request $request
     * @param string $year
     * @Route("/old-plan/{year}", name="exit_managertravelplan_old_plan", methods={"GET", "POST"})
     * @return Response
     */
    public function oldPlanAction(Request $request, $year = null)
    {
        $em = $this->getDoctrine()->getManager();
        $entriesObj = $em->getRepository('DRIExitBundle:ManagerTravelPlan')->getOldPlans();

        $yearArray = array();

        foreach ($entriesObj as $obj){
            $yearArray[date_format($obj->getCreatedAt(), 'Y')+1] = date_format($obj->getCreatedAt(), 'Y')+1;
        }

        if(!$year){
            $year = date_format(date_create(), 'Y');
        }

        $year_form = $this->createFormBuilder()
            ->add('year', ChoiceType::class, array(
                'label' => false,
                'placeholder' => 'Seleccione el Año',
                'choices'  => $yearArray,
                'attr' => array(
                    'class'=>'bs-select',
                ),
            ))
            ->getForm();

        $year_form->handleRequest($request);

        if ($year_form->isSubmitted() && $year_form->isValid()) {
            $data = $year_form->getData();
            $year = $data['year'];
            $entries = $em->getRepository('DRIExitBundle:ManagerTravelPlan')->getOldPlan($year);
        }else{
            $entries = $em->getRepository('DRIExitBundle:ManagerTravelPlan')->getOldPlan($year);
        }

        return $this->render('DRIExitBundle:ManagerTravelPlan:old-plan.html.twig', array(
            'year' => $year,
            'year_form' => $year_form->createView(),
            'entries' => $entries
        ));
    }

    /**
     * Get results from paginator and get paginator view.
     *
     * @param $queryBuilder
     * @param Request $request
     * @return array
     */
    protected function paginator($queryBuilder, Request $request)
    {
        //sorting
        $sortCol = $queryBuilder->getRootAlias().'.'.$request->get('pcg_sort_col', 'id');
        $queryBuilder->orderBy($sortCol, $request->get('pcg_sort_order', 'desc'));
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($request->get('pcg_show' , 10));

        try {
            $pagerfanta->setCurrentPage($request->get('pcg_page', 1));
        } catch (\Pagerfanta\Exception\OutOfRangeCurrentPageException $ex) {
            $pagerfanta->setCurrentPage(1);
        }
        
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me, $request)
        {
            $requestParams = $request->query->all();
            $requestParams['pcg_page'] = $page;
            return $me->generateUrl('exit_managertravelplan_index', $requestParams);
        };

        // Paginator - view
        $view = new TwitterBootstrap3View();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => 'previous',
            'next_message' => 'next',
        ));

        return array($entities, $pagerHtml);
    }

    /**
     * Calculates the total of records string
     *
     * @param $queryBuilder
     * @param $request
     * @return string
     */
    protected function getTotalOfRecordsString($queryBuilder, $request) {
        $totalOfRecords = $queryBuilder->select('COUNT(e.id)')->getQuery()->getSingleScalarResult();
        $show = $request->get('pcg_show', 10);
        $page = $request->get('pcg_page', 1);

        $startRecord = ($show * ($page - 1)) + 1;
        $endRecord = $show * $page;

        if ($endRecord > $totalOfRecords) {
            $endRecord = $totalOfRecords;
        }
        return "Showing $startRecord - $endRecord of $totalOfRecords Records.";
    }

    /**
     * Displays a form to create a new ManagerTravelPlan entity.
     *
     * @param Request $request
     * @Route("/new", name="exit_managertravelplan_new", methods={"GET", "POST"})
     * @Security("has_role('ROLE_METH_ADVISORY')")
     * @return Response
     */
    public function newAction(Request $request)
    {
        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $em = $this->getDoctrine()->getManager();

        $managerTravelPlan = new ManagerTravelPlan();

        if ($user){
            $managerTravelPlan->setCreatedBy($user);
            $managerTravelPlan->setLastUpdateBy($user);
        }

        $form   = $this->createForm('DRI\ExitBundle\Form\ManagerTravelPlanType', $managerTravelPlan, [
            'currentAction' => 'new'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($managerTravelPlan);
            $em->flush();
            
            $editLink = $this->generateUrl('exit_managertravelplan_edit', array('numberSlug' => $managerTravelPlan->getNumberSlug()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se creó una nueva Entrada del Plan de Viajes.</a>" );

            return $this->redirectToRoute('exit_managertravelplan_current_plan');
        }

        $lastVisited = $request->server->get('HTTP_REFERER');


        return $this->render('DRIExitBundle:ManagerTravelPlan:new.html.twig', array(
            'lastPage' => $lastVisited,
            'managerTravelPlan' => $managerTravelPlan,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ManagerTravelPlan entity.
     *
     * @param ManagerTravelPlan $managerTravelPlan
     * @Route("/view/{numberSlug}", name="exit_managertravelplan_show", methods={"GET"})
     * @return Response
     */
    public function showAction(ManagerTravelPlan $managerTravelPlan)
    {
        $deleteForm = $this->createDeleteForm($managerTravelPlan);
        return $this->render('DRIExitBundle:ManagerTravelPlan:show.html.twig', array(
            'managerTravelPlan' => $managerTravelPlan,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ManagerTravelPlan entity.
     *
     * @param Request $request
     * @param ManagerTravelPlan $managerTravelPlan
     * @Route("/edit/{numberSlug}", name="exit_managertravelplan_edit", methods={"GET", "POST"})
     * @Security("has_role('ROLE_METH_ADVISORY')")
     * @return Response
     */
    public function editAction(Request $request, ManagerTravelPlan $managerTravelPlan)
    {
        if (!$managerTravelPlan->isClosed()){
            $user = null;

            if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $user = $this->getUser();  // get $user object
            }

            if ($user){
                $managerTravelPlan->setLastUpdateBy($user);
            }

            $originalFinancing = new ArrayCollection();

            // Create an ArrayCollection of the current Tag objects in the database
            foreach ($managerTravelPlan->getFinancing() as $financing) {
                $originalFinancing->add($financing);
            }

            $deleteForm = $this->createDeleteForm($managerTravelPlan);
            $editForm = $this->createForm('DRI\ExitBundle\Form\ManagerTravelPlanType', $managerTravelPlan, [
                'currentAction' => 'edit'
            ])->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {

                $em = $this->getDoctrine()->getManager();

                // remove the relationship between the economic and the Application
                foreach ($originalFinancing as $financing) {
                    if (false === $managerTravelPlan->getFinancing()->contains($financing)) {
                        // remove the Task from the Tag
                        $em->remove($financing);
                    }
                }

                $em->persist($managerTravelPlan);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'La Entrada del Plan de Viajes se modificó satisfactoriamente!');
                return $this->redirectToRoute('exit_managertravelplan_current_plan');
            }

            $lastVisited = $request->server->get('HTTP_REFERER');


            return $this->render('DRIExitBundle:ManagerTravelPlan:edit.html.twig', array(
                'lastPage' => $lastVisited,
                'managerTravelPlan' => $managerTravelPlan,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }else{
            $this->get('session')->getFlashBag()->add(
                'error',
                'La Entrada del Plan de Viajes esta cerrada por lo que no se puede modificar.'
            );

            return $this->redirectToRoute('exit_managertravelplan_current_plan');
        }
    }

    /**
     * Cancel a ManagerTravelPlan entity.
     *
     * @param ManagerTravelPlan $managerTravelPlan
     * @Route("/cancel/{id}", name="exit_managertravelplan_cancel", methods={"GET"})
     * @Security("has_role('ROLE_METH_ADVISORY')")
     * @return Response
     */
    public function candelAction(ManagerTravelPlan $managerTravelPlan)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $user = null;

            if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $user = $this->getUser();  // get $user object
            }

            if ($user){
                $managerTravelPlan->setLastUpdateBy($user);
            }

            $managerTravelPlan->setCanceled(true);


            $em->persist($managerTravelPlan);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'La Entrada del Plan de Viajes se canceló satisfactoriamente!');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Existe un problema para cancelar la Entrada del Plan de Viajes');
        }

        return $this->redirectToRoute('exit_managertravelplan_current_plan');
    }

    /**
     * Deletes a ManagerTravelPlan entity.
     *
     * @param Request $request
     * @param ManagerTravelPlan $managerTravelPlan
     * @Route("/delete/{id}", name="exit_managertravelplan_delete", methods={"DELETE"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     */
    public function deleteAction(Request $request, ManagerTravelPlan $managerTravelPlan)
    {
        $form = $this->createDeleteForm($managerTravelPlan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($managerTravelPlan);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The ManagerTravelPlan was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the ManagerTravelPlan');
        }

        return $this->redirectToRoute('exit_managertravelplan_index');
    }

    /**
     * Creates a form to delete a ManagerTravelPlan entity.
     *
     * @param ManagerTravelPlan $managerTravelPlan The ManagerTravelPlan entity
     * @return FormInterface The form
     */
    private function createDeleteForm(ManagerTravelPlan $managerTravelPlan)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('exit_managertravelplan_delete', array('id' => $managerTravelPlan->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete ManagerTravelPlan by id
     *
     * @param ManagerTravelPlan $managerTravelPlan
     * @Route("/delete-by-id/{id}", name="exit_managertravelplan_by_id_delete", methods={"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     */
    public function deleteByIdAction(ManagerTravelPlan $managerTravelPlan){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($managerTravelPlan);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The ManagerTravelPlan was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the ManagerTravelPlan');
        }

        return $this->redirect($this->generateUrl('exit_managertravelplan_index'));
    }

    /**
     * Bulk Action
     *
     * @param Request $request
     * @Route("/bulk/delete", name="exit_managertravelplan_bulk_action", methods={"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
    */
    public function bulkAction(Request $request)
    {
        $ids = $request->get("ids", array());
        $action = $request->get("bulk_action", "delete");

        if ($action == "delete") {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('DRIExitBundle:ManagerTravelPlan');

                foreach ($ids as $id) {
                    $managerTravelPlan = $repository->find($id);
                    $em->remove($managerTravelPlan);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'managerTravelPlans was deleted successfully!');

            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the managerTravelPlans ');
            }
        }

        return $this->redirect($this->generateUrl('exit_managertravelplan_index'));
    }

    /**
     * Generate and save a ManagerTravelPlan to Word
     *
     * @param string $year
     * @Route("/plan-to/word/{year}", name="exit_managertravelplan_to_word")
     * @return BinaryFileResponse
     * @throws Exception
     */
    public function planToWordAction($year = null)
    {
        $em = $this->getDoctrine()->getManager();
        $mtpRepo = $em->getRepository('DRIExitBundle:ManagerTravelPlan');
        $currentDate = sprintf('%s', date('d/m/Y'));
        $entriesArray = array();
        $name = 'Plan de Salidas al Exterior ';
        $template = "report_templates/manager_travel_plan_template.docx";

        $filename = sprintf('%s - %s.docx', $name, date('Y')+1);
        $entries = $mtpRepo->getCurrentPlan();

        if ($year){
            $filename = sprintf('%s - %s.docx', $name, $year);
            $entries = $mtpRepo->getOldPlan($year);
        }

        /*
         * todo
         * Remodelar el repositorio
         */

        foreach ($entries as $entry) {
            $client = $entry->getClient();

            $fullName = $client->getFullName();
            $position = $client->getWorkersPosition();
            $countries = '';
            foreach ($entry->getCountries() as $country){
                $countries .= $country->getSpName().'</w:t><w:br/><w:t>';
            }
            $objetives = $entry->getObjetives();
            $departureDate = sprintf('%s', date_format($entry->getDepartureDate(), 'm'));
            switch ($departureDate){
                case '01': $departureDate = 'Enero'; break;
                case '02': $departureDate = 'Febrero'; break;
                case '03': $departureDate = 'Marzo'; break;
                case '04': $departureDate = 'Abril'; break;
                case '05': $departureDate = 'Mayo'; break;
                case '06': $departureDate = 'Junio'; break;
                case '07': $departureDate = 'Julio'; break;
                case '08': $departureDate = 'Agosto'; break;
                case '09': $departureDate = 'Septiembre'; break;
                case '10': $departureDate = 'Octubre'; break;
                case '11': $departureDate = 'Noviembre'; break;
                case '12': $departureDate = 'Diciembre'; break;
                default : $departureDate = '-'; break;
            }
            $lapsed = $entry->getLapsed();
            $peFinancing = '';
            $pcFinancing = '';
            foreach ($entry->getFinancing() as $financing){
                if($financing->getSource() == 'PE'){
                    $peFinancing .= $financing->getType().': '.$financing->getAmount().''.$financing->getCurrency().'</w:t><w:br/><w:t>';
                }else{
                    $pcFinancing .= $financing->getType().': '.$financing->getAmount().''.$financing->getCurrency().'</w:t><w:br/><w:t>';
                }
            }

            $entriesArray[] = [
                'fullName'      => $fullName,
                'position'      => $position,
                'countries'     => $countries,
                'objetives'     => $objetives,
                'departureDate' => $departureDate,
                'lapsed'        => $lapsed,
                'peFinancing'   => $peFinancing,
                'pcFinancing'   => $pcFinancing,
            ];
        }

        //var_dump($agreementsArray);
        // ask the service for a Word2007
        $templateObj = $this->get('phpword')->createTemplateObject($template);

        $templateObj->setValue('year', $year);
        $templateObj->setValue('currentDate', $currentDate);

        $templateObj->cloneRowAndSetValues('fullName', $entriesArray);

        $templateOut = $templateObj->save();

        $response = new BinaryFileResponse($templateOut);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }
}
