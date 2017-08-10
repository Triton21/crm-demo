<?php

namespace AppBundle\Entity;

/**
 * RtemplateRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RtemplateRepository extends \Doctrine\ORM\EntityRepository
{
    public function findatt1($attid) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.attach1 = :attid')
                ->addOrderBy('c.id')
                ->setParameters(array('attid' => $attid));

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function findatt2($attid) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.attach2 = :attid')
                ->addOrderBy('c.id')
                ->setParameters(array('attid' => $attid));

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function findatt3($attid) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.attach3 = :attid')
                ->addOrderBy('c.id')
                ->setParameters(array('attid' => $attid));

        return $qb->getQuery()
                        ->getResult();
    }
}
