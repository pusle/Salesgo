<?php

namespace LeadBundle\Twig;

class LeadExtension extends \Twig_Extension
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getdashboardleads', array($this, 'getDashboardLeads'))
        );
    }

    public function getDashboardLeads($count)
    {
        return $this->container->get('lead.dashboard_leads')->getDashboardLeads($count);
    }

    public function getName()
    {
        return 'lead.extension';
    }
}
