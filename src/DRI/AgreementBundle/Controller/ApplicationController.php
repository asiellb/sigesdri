<?php

namespace DRI\AgreementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\BinaryFileResponse,
    Symfony\Component\HttpFoundation\ResponseHeaderBag,
    Symfony\Component\Routing\Annotation\Route,
    Symfony\Component\Security\Acl\Exception\Exception,
    Symfony\Component\Security\Core\Exception\AccessDeniedException,
    Symfony\Component\Form\FormInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use DRI\AgreementBundle\Entity\Application;
use DRI\UsefulBundle\Useful\Useful;

/**
 * Application controller.
 *
 * @Route("/application")
 */
class ApplicationController extends Controller
{
    /**
     * Lists all Institutional Agreements entities.
     *
     * @param Request $request
     * @Route("/index", name="agreement_application_index", methods={"GET"})
     * @return Response
     * @throws \Exception
     */
    public function indexAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('app.datatable.agreements.application');
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('DRIAgreementBundle:Application:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Displays a form to create a new Application entity.
     *
     * @param Request $request
     * @Route("/new", name="agreement_application_new", methods={"GET", "POST"})
     * @Security("has_role('ROLE_MANAGE_SPECIALIST')")
     * @return Response
     */
    public function newAction(Request $request)
    {
        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        $application = new Application();

        if ($user){
            $application->setCreatedBy($user);
        }

        $form   = $this->createForm('DRI\AgreementBundle\Form\ApplicationType', $application, [
            'currentAction' => 'new'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();
            
            $editLink = $this->generateUrl('agreement_application_edit', array('id' => $application->getId()));
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>Se creó una nueva Ficha de Consulta de Convenios.</a>" );

            return $this->redirectToRoute('agreement_application_show', array('id' => $application->getId()));
        }

        $lastVisited = $request->server->get('HTTP_REFERER');


        return $this->render('DRIAgreementBundle:Application:new.html.twig', array(
            'lastPage' => $lastVisited,
            'application' => $application,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Application entity.
     *
     * @param Application $application
     * @Route("/{id}", name="agreement_application_show", options = {"expose" = true}, methods={"GET"})
     * @return Response
     */
    public function showAction(Application $application)
    {
        $deleteForm = $this->createDeleteForm($application);
        return $this->render('DRIAgreementBundle:Application:show.html.twig', array(
            'application' => $application,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Application entity.
     *
     * @param Request $request
     * @param Application $application
     * @Route("/edit/{id}", name="agreement_application_edit", options = {"expose" = true}, methods={"GET", "POST"})
     * @Security("has_role('ROLE_MANAGE_SPECIALIST')")
     * @return Response
     */
    public function editAction(Request $request, Application $application)
    {
        $user = null;

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->getUser();  // get $user object
        }

        if ($user){
            $application->setLastUpdateBy($user);
        }

        $deleteForm = $this->createDeleteForm($application);
        $editForm = $this->createForm('DRI\AgreementBundle\Form\ApplicationType', $application, [
            'currentAction' => 'edit'
        ])->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'La Ficha de Consulta fué editada satisfactoriamente!');

            return $this->redirectToRoute('agreement_application_show', array('id' => $application->getId()));
        }

        $lastVisited = $request->server->get('HTTP_REFERER');

        return $this->render('DRIAgreementBundle:Application:/edit.html.twig', array(
            'lastPage' => $lastVisited,
            'application' => $application,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Application entity.
     *
     * @param Request $request
     * @param Application $application
     * @Route("/{id}", name="agreement_application_delete", methods={"DELETE"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     */
    public function deleteAction(Request $request, Application $application)
    {
    
        $form = $this->createDeleteForm($application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($application);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Application was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Application');
        }
        
        return $this->redirectToRoute('agreement_application_index');
    }
    
    /**
     * Creates a form to delete a Application entity.
     *
     * @param Application $application The Application entity
     * @return FormInterface The form
     */
    private function createDeleteForm(Application $application)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('agreement_application_delete', array('id' => $application->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Delete Application by id
     *
     * @param Application $application The entity id
     * @Route("/delete/{id}", name="agreement_application_by_id_delete", methods={"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     */
    public function deleteByIdAction(Application $application){
        $em = $this->getDoctrine()->getManager();
        
        try {
            $em->remove($application);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Application was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Application');
        }

        return $this->redirect($this->generateUrl('agreement_application_index'));

    }

    /**
     * Bulk Action
     *
     * @param Request $request
     * @Route("/bulk-action/", name="agreement_application_bulk_delete", methods={"POST"})
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
            $repository = $em->getRepository('DRIAgreementBundle:Application');

            $counter = 0;

            foreach ($choices as $choice) {
                $entity = $repository->find($choice['id']);
                $em->remove($entity);
                $counter++;
            }

            $em->flush();

            return new Response('Se borraron satisfactoriamente '.$counter.' Fichas de Consulta', 200);
        }


        return new Response('Solicitud incorrecta', 400);
    }

    /**
     * Generate and save a ManagerTravelPlan to Word
     *
     * @param Application $application
     * @Route("/to/word/{id}", name="agreement_application_to_word")
     * @return BinaryFileResponse
     * @throws \Exception
     */
    public function applicationToWordAction(Application $application)
    {
        $currentPeriod = Useful::getCurrentPeriod();
        $currentDate = sprintf('%s', date('d/m/Y'));

        $name = 'Ficha de Consulta de Convenios ';
        $filename = sprintf('%s - %s.docx', $name, $application->getNumber());
        $template = "report_templates/agreements_consult_sheet.docx";

        //Vars declaration
        $ces = 'Universidad de Camagüey';
        $institution = $application->getInstitution()->getName();
        $address = $application->getInstitution()->getAddress();
        $background = $application->getBackground();
        $objetives = $application->getObjetives();
        $basement = $application->getBasement();
        $commitments = $application->getCommitments();
        $validity = $application->getValidity();
        $memberForCubanPart = $application->getMemberForCubanPart();
        $memberForForeignPart = $application->getMemberForForeignPart();
        $results = $application->getResults();
        $expenses = $application->getExpenses().' CUC';


        //var_dump($agreementsArray);
        // ask the service for a Word2007
        $templateObj = $this->get('phpword')->createTemplateObject($template);

        $templateObj->setValue('currentPeriod', $currentPeriod);
        $templateObj->setValue('currentDate', $currentDate);
        $templateObj->setValue('ces', $ces);
        $templateObj->setValue('institution', $institution);
        $templateObj->setValue('address', $address);
        $templateObj->setValue('background', $background);
        $templateObj->setValue('objetives', $objetives);
        $templateObj->setValue('basement', $basement);
        $templateObj->setValue('commitments', $commitments);
        $templateObj->setValue('validity', $validity);
        $templateObj->setValue('memberForCubanPart', $memberForCubanPart);
        $templateObj->setValue('memberForForeignPart', $memberForForeignPart);
        $templateObj->setValue('results', $results);
        $templateObj->setValue('expenses', $expenses);


        $templateOut = $templateObj->save();

        $response = new BinaryFileResponse($templateOut);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }
}
