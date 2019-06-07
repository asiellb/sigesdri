<?php

namespace DRI\ForeingStudentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForeingStudentController extends Controller
{
    /**
     * @Route("/", name="foreingstudent_index")
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('DRIForeingStudentBundle:Default:index.html.twig');
    }
}
