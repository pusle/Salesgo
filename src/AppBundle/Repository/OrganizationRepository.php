<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class OrganizationRepository extends EntityRepository
{
    public function findNumberOfOrganizations()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $query = $qb->select('COUNT(o) as numOrganizations')
            ->from('AppBundle:Organization', 'o')
            ->getQuery();

        try {
            return $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findAllOrderedByPermits($start, $results, $orderBy, $orderDirection, $searchString)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('o as Organization, COUNT(p) as numPermits');

        if ($searchString) $qb->where($qb->expr()->like('o.name', ':search'))->setParameter('search', '%' . $searchString . '%');

        $qb->from('AppBundle:Organization', 'o');
        $qb->leftJoin('o.permits', 'p');
        $qb->groupBy('p.organization');

        $orderDirection = $orderDirection == '' ? 'DESC' : 'ASC';
        if ($orderBy == 'employees') $qb->orderBy('o.numEmployees', $orderDirection);
        if ($orderBy == 'name') $qb->orderBy('o.name', $orderDirection);
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
}
