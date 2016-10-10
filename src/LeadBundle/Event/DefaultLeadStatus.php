<?php

namespace LeadBundle\Event;

use Doctrine\ORM\EntityManager;

class DefaultLeadStatus
{
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }


}
