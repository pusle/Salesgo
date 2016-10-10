<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Dashboard controller.
 */
class DashboardController extends Controller  {
    /**
     * @Route("/", name="dashboard")
     */
    public function indexAction(Request $request)
    {
        // $em = $this->getDoctrine()->getManager();

        // replace this example code with whatever you need
        return $this->render('dashboard/index.html.twig', array(
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ));
    }
}
