<?php

namespace DRI\SystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SystemController extends Controller
{
    /**
     * @Route("/homepage", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clientsCount = $em->getRepository('DRIClientBundle:Client')->findAll();
        $passportsCount = $em->getRepository('DRIPassportBundle:Passport')->findAll();

        return $this->render('DRISystemBundle:system:index.html.twig', [
            'clientsCount' => $clientsCount,
            'passportsCount' => $passportsCount,
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render('DRISystemBundle:system:about.html.twig');
    }
}
