<?php

namespace TodoBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class DashboardTodoService
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

    public function getLatestTodos($count)
    {
        $todos = $this->em->getRepository('TodoBundle:Todo')
            ->findBy(array('done' => false), array('id' => 'DESC'), $count);

        $forms = array();
        foreach ($todos as $todo) {
            $forms[] = $this->formFactory->createNamed('form', 'TodoBundle\Form\TodoType', $todo, array(
                'action' => $this->router->generate('todo_complete', array('id' => $todo->getId())),
                'method' => 'POST'
            ))->createView();
        }

        return $forms;
    }
}
