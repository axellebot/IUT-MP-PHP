<?php

namespace IUT\QCMBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Route("/", name="default_security_target")
     */
    public function indexAction()
    {
        $questionnaires = $this->getDoctrine()
            ->getRepository('IUTQCMBundle:Questionnaire')
            ->findAll();

        return $this->render('IUTQCMBundle:Default:index.html.twig', array('questionnaires' => $questionnaires));
    }
}
