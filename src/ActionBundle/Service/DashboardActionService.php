<?php

namespace ActionBundle\Service;

use Doctrine\ORM\EntityManager;

class DashboardActionService
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getDashboardActions($count)
    {
        $actions = $this->em->getRepository('ActionBundle:Action')
                          ->findByLongestAgoAndNotDone($count);

        return $actions;
    }
}
