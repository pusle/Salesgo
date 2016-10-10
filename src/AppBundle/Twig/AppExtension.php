<?php
/**
 * Created by PhpStorm.
 * User: tommy
 * Date: 16.09.2016
 * Time: 13.33
 */

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getcontactmethodsforcontact', array($this, 'getContactMethodsForContact'))
        );
    }

    public function getContactMethodsForContact($contactId)
    {
        return $this->container->get('contactmethod.methods')->getContactMethodsForContact($contactId);
    }

    public function getName()
    {
        return 'app.extension';
    }
}
