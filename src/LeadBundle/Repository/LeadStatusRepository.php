<?php

namespace LeadBundle\Repository;

use Doctrine\ORM\EntityRepository;

class LeadStatusRepository extends EntityRepository
{
    public function findByLatestGroupByContact($count)
    {
        $qb = $this->createQueryBuilder('l')
            ->where('l.leadStatusType <> 1')
            ->setMaxResults(5)
            ->orderBy('l.changedAt', 'ASC')
            ->distinct()
            ->groupBy('l.contact')
            ->setMaxResults($count);

        $query = $qb->getQuery();

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
