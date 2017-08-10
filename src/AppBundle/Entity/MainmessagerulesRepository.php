<?php

namespace AppBundle\Entity;

/**
 * MainmessagerulesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MainmessagerulesRepository extends \Doctrine\ORM\EntityRepository
{
    public function findallrules($id) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.id')
                ->where('c.settid = :id')
                ->setParameters(array('id' => $id));

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function selectallrules($id, $type) {

        $qb = $this->createQueryBuilder('c')
                ->select('c.filtertext, c.folder')
                ->addOrderBy('c.id')
                ->where('c.settid = :id', 'c.type = :type')
                ->setParameters(array('id' => $id, 'type' => $type));

        return $qb->getQuery()
                        ->getResult();
    }
    
    
    
}
