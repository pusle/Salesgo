<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class ContactMethodService
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

    public function getContactMethodsForContact($contactId)
    {
        $contact = $this
            ->em
            ->getRepository('AppBundle:Contact')
            ->findOneBy(array('id' => $contactId));

        $contactMethods = $this
            ->em
            ->getRepository('AppBundle:ContactMethod')
            ->findBy(array('contact' => $contact->getId()));

        $forms = array();
        foreach ($contactMethods as $contactMethod) {
            $forms[] = $this->formFactory->createNamed('form', 'AppBundle\Form\ContactMethodType', $contactMethod, array(
                'action' => $this->router->generate('contactmethod_delete', array('id' => $contactMethod->getId())),
                'method' => 'DELETE'
            ))->createView();
        }

        return $forms;
    }
}
