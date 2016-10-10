<?php

namespace TodoBundle\Twig;

class TodoExtension extends \Twig_Extension
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getlatesttodos', array($this, 'getLatestTodos'))
        );
    }

    public function getLatestTodos($count)
    {
        return $this->container->get('todo.todos')->getLatestTodos($count);
    }

    public function getName()
    {
        return 'todo.extension';
    }
}
