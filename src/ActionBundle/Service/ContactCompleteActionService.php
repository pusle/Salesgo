<?php

namespace ActionBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class ContactCompleteActionService
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

    public function getActionCompleteForm($actionId)
    {
        $action = $this->em->getRepository('ActionBundle:Action')->find($actionId);

        return $this->formFactory->createNamed('form', 'ActionBundle\Form\ActionType', $action, array(
            'action' => $this->router->generate('action_edit', array('id' => $actionId)),
            'method' => 'POST'
        ))->createView();
    }
}
