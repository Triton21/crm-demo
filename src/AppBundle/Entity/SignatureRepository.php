<?php

namespace AppBundle\Entity;

/**
 * SignatureRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SignatureRepository extends \Doctrine\ORM\EntityRepository
{
    public function findmysignature($name) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.id')
                ->where('c.username = :name')
                ->setParameters(array('name' => $name));

        return $qb->getQuery()
                        ->getResult();
    }
    
    
}
