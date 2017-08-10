<?php

namespace AppBundle\Entity;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function findbylistname($name) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.listname = :listname')
                ->addOrderBy('c.id')
                ->setParameters(array('listname' => $name));

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function findmyfiles($listname) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.listname = :listname')
                ->addOrderBy('c.id')
                ->setParameters(array('listname' => $listname));

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function findallbyemail($id) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.emailid = :id')
                ->addOrderBy('c.id')
                ->setParameters(array('id' => $id));

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function findmyattachments($id) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.id')
                ->where('c.emailid = :id')
                ->setParameters(array('id' => $id));

        return $qb->getQuery()
                        ->getResult();
    }
    
    
    
    
}
