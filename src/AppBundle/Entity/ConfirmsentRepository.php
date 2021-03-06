<?php

namespace AppBundle\Entity;

/**
 * ConfirmsentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConfirmsentRepository extends \Doctrine\ORM\EntityRepository
{
    public function countall() {
        $qb = $this->createQueryBuilder('c')
                ->select('count(c)');
        return $qb->getQuery()->getSingleScalarResult();
    }
    
     public function findConfSentbylimit($limit, $offset) {
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.id', 'DESC')
                ->setMaxResults($limit)
                ->setFirstResult($offset);
        return $qb->getQuery()->getResult();
    }
    
    public function searchNameEmailBody($searchTerm, $offset, $limit) {

        $qb = $this->createQueryBuilder('n');
        $result = $qb->select('n')
                ->where($qb->expr()->like('n.customerName', $qb->expr()->literal('%' . $searchTerm . '%')))
                ->orWhere($qb->expr()->like('n.customerEmail', $qb->expr()->literal('%' . $searchTerm . '%')))
                ->addOrderBy('n.id', 'DESC')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        return $result;
    }
    
    public function countAllsearchToNumberBody($searchTerm) {

        $qb = $this->createQueryBuilder('n');
        $result = $qb->select('count(n)')
                ->where($qb->expr()->like('n.customerName', $qb->expr()->literal('%' . $searchTerm . '%')))
                ->orWhere($qb->expr()->like('n.customerEmail', $qb->expr()->literal('%' . $searchTerm . '%')))
                ->getQuery()
                ->getSingleScalarResult();
        return $result;
    }
    
    public function searchLeadsByName($name) {

        $qb = $this->createQueryBuilder('n');
        $result = $qb->select('n')
                ->where($qb->expr()->like('n.customerName', $qb->expr()->literal('%' . $name . '%')))
                ->getQuery()
                ->getResult();
        return $result;
    }
    
    public function searchLeadsByEmail($email) {

        $qb = $this->createQueryBuilder('n');
        $result = $qb->select('n')
                ->where($qb->expr()->like('n.customerEmail', $qb->expr()->literal('%' . $email . '%')))
                ->getQuery()
                ->getResult();
        return $result;
    }
    
    public function findTempConf($limit, $offset) {
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.id', 'ASC')
                ->setMaxResults($limit)
                ->setFirstResult($offset);

        try {
            return $qb->getQuery()->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
    
}
