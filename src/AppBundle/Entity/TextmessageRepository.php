<?php

namespace AppBundle\Entity;

/**
 * TextmessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TextmessageRepository extends \Doctrine\ORM\EntityRepository
{
    public function findSent($limit, $offset) {
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.messageType = :messageType')
                ->addOrderBy('c.id', 'DESC')
                ->setParameters(array('messageType' => 0))
                ->setFirstResult($offset)
                ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
    
    public function countAllSent() {
        $qb = $this->createQueryBuilder('c')
                ->select('count(c)')
                ->where('c.messageType = :messageType')
                ->setParameters(array('messageType' => 0));

        return $qb->getQuery()->getSingleScalarResult();
    }
    
    public function findReceived($limit, $offset) {
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.messageType = :messageType')
                ->addOrderBy('c.id', 'DESC')
                ->setParameters(array('messageType' => 1))
                ->setFirstResult($offset)
                ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
    
    public function countAllReceived() {
        $qb = $this->createQueryBuilder('c')
                ->select('count(c)')
                ->where('c.messageType = :messageType')
                ->setParameters(array('messageType' => 1));

        return $qb->getQuery()->getSingleScalarResult();
    }
    
    public function findById($limit, $offset, $id) {
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.leadId = :id')
                ->setParameters(array('id' => $id))
                ->setFirstResult($offset)
                ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
    
    public function countAllById($id) {
        $qb = $this->createQueryBuilder('c')
                ->select('count(c)')
                ->where('c.leadId = :id')
                ->setParameters(array('id' => $id));

        return $qb->getQuery()->getSingleScalarResult();
    }
    
    public function searchToNumberBody($searchTerm, $offset, $limit) {

        $qb = $this->createQueryBuilder('n');
        $result = $qb->select('n')
                ->where($qb->expr()->like('n.toNumber', $qb->expr()->literal('%' . $searchTerm . '%')))
                ->orWhere($qb->expr()->like('n.body', $qb->expr()->literal('%' . $searchTerm . '%')))
                ->andWhere('n.messageType = :type')
                ->addOrderBy('n.id', 'DESC')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->setParameters(array('type' => 1))
                ->getQuery()
                ->getResult();
        return $result;
    }
    
    public function countAllsearchToNumberBody($searchTerm) {

        $qb = $this->createQueryBuilder('n');
        $result = $qb->select('count(n)')
                ->where($qb->expr()->like('n.toNumber', $qb->expr()->literal('%' . $searchTerm . '%')))
                ->orWhere($qb->expr()->like('n.body', $qb->expr()->literal('%' . $searchTerm . '%')))
                ->andWhere('n.messageType = :type')
                ->setParameters(array('type' => 1))
                ->getQuery()
                ->getSingleScalarResult();
        return $result;
    }
    
    public function countAllsearchSentToNumberBody($searchTerm) {

        $qb = $this->createQueryBuilder('n');
        $result = $qb->select('count(n)')
                ->where($qb->expr()->like('n.toNumber', $qb->expr()->literal('%' . $searchTerm . '%')))
                ->orWhere($qb->expr()->like('n.body', $qb->expr()->literal('%' . $searchTerm . '%')))
                ->andWhere('n.messageType = :type')
                ->setParameters(array('type' => 0))
                ->getQuery()
                ->getSingleScalarResult();
        return $result;
    }
    
    public function searchSentToNumberBody($searchTerm, $offset, $limit) {

        $qb = $this->createQueryBuilder('n');
        $result = $qb->select('n')
                ->where($qb->expr()->like('n.toNumber', $qb->expr()->literal('%' . $searchTerm . '%')))
                ->orWhere($qb->expr()->like('n.body', $qb->expr()->literal('%' . $searchTerm . '%')))
                ->andWhere('n.messageType = :type')
                ->addOrderBy('n.id', 'DESC')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->setParameters(array('type' => 0))
                ->getQuery()
                ->getResult();
        return $result;
    }
    
}
