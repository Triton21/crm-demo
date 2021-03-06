<?php

namespace AppBundle\Entity;

/**
 * MaininboxRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MainsentRepository extends \Doctrine\ORM\EntityRepository {

    public function findmyemails($id, $offset, $itemperpage) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.id', 'DESC')
                ->where('c.settid = :id')
                ->setParameters(array('id' => $id))
                ->setFirstResult($offset)
                ->setMaxResults($itemperpage);

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function findconversation($email) {
        $fields = array('c.id', 'c.fromemail', 'c.maildate', 'c.content', 'c.toemail', 'c.username', 'c.subject');
        $qb = $this->createQueryBuilder('c')
                ->select($fields)
                ->addOrderBy('c.id', 'DESC')
                ->where('c.toemail = :email')
                ->setParameters(array('email' => $email));
                //->setFirstResult($offset)
                //->setMaxResults($itemperpage);

        return $qb->getQuery()
                        ->getResult();
    }

    public function countall($id) {

        $qb = $this->createQueryBuilder('c')
                ->select('count(c)')
                ->where('c.settid = :id')
                ->setParameters(array('id' => $id));
        
        return $qb->getQuery()
                        ->getSingleScalarResult();
    }
    
    public function findnullentities($id) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                //->addOrderBy('c.id', 'DESC')
                ->where('c.settid = :id', 'c.fromemail is NULL')
                
                ->setParameters(array('id' => $id));

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function searchemail($id, $offset, $limit, $mysearchemail) {

        $qb = $this->createQueryBuilder('n')
                ->select('n')
                ->addOrderBy('n.id', 'DESC')
                ->where('n.settid = :id', 'n.toemail = :mysearchemail')
                ->setParameters(array('id' => $id, 'mysearchemail' =>$mysearchemail ))
                ->setFirstResult($offset)
                ->setMaxResults($limit);
                
       return $qb->getQuery()
                        ->getResult();
    }
    
    public function searchemailtotalcount($id, $mysearchemail) {

        $qb = $this->createQueryBuilder('n')
                ->select('count(n)')
                ->where('n.settid = :id', 'n.toemail = :mysearchemail')
                ->setParameters(array('id' => $id, 'mysearchemail' =>$mysearchemail ));
                
       return $qb->getQuery()
                        ->getSingleScalarResult();
    }
    
    public function findOneEmail($id, $searchdata) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                //->addOrderBy('c.id', 'DESC')
                ->where('c.settid = :id', 'c.toemail = :searchdata')
                ->setParameters(array('id' => $id, 'searchdata' => $searchdata))
                ->setMaxResults(1);

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function findOneName($id, $searchdata) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                //->addOrderBy('c.id', 'DESC')
                ->where('c.settid = :id', 'c.toname = :searchdata')
                ->setParameters(array('id' => $id, 'searchdata' => $searchdata))
                ->setMaxResults(1);

        return $qb->getQuery()
                        ->getResult();
    }
    
    
    
}