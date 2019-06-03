<?php

namespace DRI\ForeingStudentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/index", name="foreingstudent_index")
     */
    public function indexAction()
    {
        return $this->render('DRIForeingStudentBundle:Default:index.html.twig');
    }
}
