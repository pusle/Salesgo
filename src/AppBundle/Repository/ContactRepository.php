<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ContactRepository extends EntityRepository
{
    public function findAllOrderedByPermits($start, $results, $orderBy, $orderDirection)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('c as Contact, COUNT(p) as numPermits');
        $qb->from('AppBundle:Contact', 'c');
        $qb->leftJoin('c.organization', 'o');
        $qb->leftJoin('o.permits', 'p');
        $qb->groupBy('p.organization');

        $orderDirection = $orderDirection == '' ? 'DESC' : 'ASC';
        if ($orderBy == 'employees') $qb->orderBy('o.numEmployees', $orderDirection);
        if ($orderBy == 'name') $qb->orderBy('c.name', $orderDirection);
        if ($orderBy == '') $qb->orderBy('numPermits', $orderDirection);

        $qb->addOrderBy('o.numEmployees', 'DESC');

        $qb->setFirstResult($start);
        $qb->setMaxResults($results);

        $query = $qb->getQuery();

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findNumberOfContacts()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $query = $qb->select('COUNT(c) as numContacts')
                    ->from('AppBundle:Contact', 'c')
                    ->getQuery();

        try {
            return $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findAllForPaginator($start, $results)
    {
        $query = $this->createQueryBuilder('c')
            ->setFirstResult($start)
            ->setMaxResults($results)
            ->getQuery();
        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
