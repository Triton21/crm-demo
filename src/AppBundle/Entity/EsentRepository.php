<?php

namespace AppBundle\Entity;

/**
 * EsentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EsentRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllByTaskid($taskid) {
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.taskId = :taskId')
                ->addOrderBy('c.id')
                ->setParameters(array('taskId' => $taskid));

        try {
            return $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
    
    public function findAllByTaskidajax($taskid) {
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.taskId = :taskId')
                ->addOrderBy('c.id')
                ->setParameters(array('taskId' => $taskid));

        try {
            return $qb->getQuery()->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    
    
}
