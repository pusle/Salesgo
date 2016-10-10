<?php

namespace LeadBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class DashboardLeadService
{
    private $em;
    private $formFactory;
    private $router;

    public function __construct(EntityManager $entityManager, FormFactory $formFactory, Router $router)
    {
        $this->em = $entityManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    public function getDashboardLeads($count)
    {
        $leads = $this->em->getRepository('LeadBundle:LeadStatus')
            ->findByLatestGroupByContact($count);

        return $leads;
    }
}
