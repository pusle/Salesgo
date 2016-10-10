<?php

namespace ActionBundle\Twig;

class ActionExtension extends \Twig_Extension
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getdashboardactions', array($this, 'getDashboardActions')),
            new \Twig_SimpleFunction('getcontactactioncompleteform', array($this, 'getContactActionCompleteForm'))
        );
    }

    public function getDashboardActions($count)
    {
        return $this->container->get('action.dashboard_actions')->getDashboardActions($count);
    }

    public function getContactActionCompleteForm($actionId)
    {
        return $this->container->get('action.contact_complete_action')->getActionCompleteForm($actionId);
    }

    public function getName()
    {
        return 'action.extension';
    }
}
