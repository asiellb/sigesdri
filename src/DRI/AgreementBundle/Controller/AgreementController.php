<?php

namespace DRI\AgreementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use DRI\AgreementBundle\Entity\Institutional;
use DRI\UsefulBundle\Useful\Useful;

class AgreementController extends Controller
{
    /**
     * @Route("/", name="agreement_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $instRepo   = $em->getRepository('DRIAgreementBundle:Institutional');

        $agreements = $instRepo->findAll();

        return $this->render('DRIAgreementBundle:Agreement:index.html.twig', array(
            'agreements' => $agreements,
        ));
    }

    /**
     * @Route("/set-institution-slug", name="set_institution_slug")
     */
    public function setInsSlugAction()
    {
        $em = $this->getDoctrine()->getManager();
        $instRepo   = $em->getRepository('DRIAgreementBundle:Institution');

        $institutions = $instRepo->findAll();

        foreach ($institutions as $institution){
            $institution->setNameSlug(Useful::getSlug($institution->getName()));
            $em->persist($institution);
            $em->flush();
        }

    }

    /**
     * @Route("/set-ins-num", name="set_ins_num")
     */
    public function setInsNumAction()
    {
        $em = $this->getDoctrine()->getManager();
        $instRepo   = $em->getRepository('DRIAgreementBundle:Institutional');

        $institutions = $instRepo->findAll();

        foreach ($institutions as $institution){
            $institution->setNumber(Useful::getSlug($institution->getId()));
            $em->persist($institution);
            $em->flush();
        }

    }

    /**
     * @Route("/set-institution-acronim", name="set_institution_acronim")
     */
    public function setInsAcronimAction()
    {
        $em = $this->getDoctrine()->getManager();
        $instRepo   = $em->getRepository('DRIAgreementBundle:Institution');

        $institutions = $instRepo->findAll();

        foreach ($institutions as $institution){
            preg_match_all('#([A-Z]+)#',$institution->getName(),$matches);

            $institution->setAcronym(implode('',$matches[1]));

            $em->persist($institution);
            $em->flush();
        }

    }
}
