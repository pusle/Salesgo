<?php

namespace ActionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ActionRepository extends EntityRepository
{
    public function findByLongestAgoAndNotDone($count)
    {
        $qb = $this->createQueryBuilder('a')
                   ->where('a.done <> 1')
                   ->setMaxResults(5)
                   ->orderBy('a.startedAt', 'ASC')
                   ->groupBy('a.contact')
                    ->setMaxResults($count);

        $query = $qb->getQuery();

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
