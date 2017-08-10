<?php

namespace AppBundle\Entity;

/**
 * LeadRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LeadRepository extends \Doctrine\ORM\EntityRepository {

    public function autocomplete() {

        $qb = $this->createQueryBuilder('c')
                ->select('c.customerName', 'c.id', 'c.customerEmail', 'c.customerTel')
                ->addOrderBy('c.id');
        return $qb->getQuery()
                        ->getResult();
    }
    
    public function findlastid() {

        $qb = $this->createQueryBuilder('c')
                ->select('c.id')
                ->addOrderBy('c.id', 'DESC')
                ->setMaxResults(1);

        return $qb->getQuery()
                        ->getSingleScalarResult();
    }
    
    public function findmyleads($name) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.assign = :assign')
                ->addOrderBy('c.id')
                ->setParameters(array('assign' => $name));

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function findAllbyPage($offset, $itemperpage) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.id')
                ->setFirstResult($offset)
                ->setMaxResults($itemperpage);

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function findNotReachedLastWeek($startdate, $finishdate) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.id', 'DESC')
                ->where('c.contacted = :contacted', 'c.status = :status')
                ->andwhere('c.createdAt > :startdate', 'c.createdAt < :finishdate')
                ->setParameters(array('status' => 'In progress', 'contacted' => 0, 'startdate' => $startdate, 'finishdate' => $finishdate ))
                ->setMaxResults(50);

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function countNotReachedLastWeek($startdate, $finishdate) {

        $qb = $this->createQueryBuilder('c')
                ->select('count(c)')
                ->addOrderBy('c.id', 'DESC')
                ->where('c.contacted = :contacted', 'c.status = :status')
                ->andwhere('c.createdAt > :startdate', 'c.createdAt < :finishdate')
                ->setParameters(array('status' => 'In progress', 'contacted' => 0, 'startdate' => $startdate, 'finishdate' => $finishdate ))
                ->setMaxResults(50);

        return $qb->getQuery()
                        ->getOneOrNullResult();
    }

    public function findleadsbystatus($status) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.status = :status')
                ->addOrderBy('c.id')
                ->setParameters(array('status' => $status));

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function countLeadByStatus($status) {

        $qb = $this->createQueryBuilder('c')
                ->select('count(c)')
                ->where('c.status = :status')
                ->addOrderBy('c.id')
                ->setParameters(array('status' => $status));

        return $qb->getQuery()
                        ->getSingleScalarResult();
    }
    
    

    public function findLeadsByStatusasc($status, $offset, $itemperpage, $myfilter) {
        if ($myfilter != 'all') {
            $qb = $this->createQueryBuilder('c')
                    ->select('c')
                    ->where('c.status = :status')
                    ->andwhere('c.contacted = :myfilter')
                    //->addOrderBy('c.createdAt', 'ASC')
                    ->setParameters(array('status' => $status, 'myfilter' => $myfilter))
                    ->setFirstResult($offset)
                    ->setMaxResults($itemperpage);

            return $qb->getQuery()
                            ->getResult();
        } else {
            $qb = $this->createQueryBuilder('c')
                    ->select('c')
                    ->where('c.status = :status')
                    //->addOrderBy('c.createdAt', 'ASC')
                    ->setParameters(array('status' => $status))
                    ->setFirstResult($offset)
                    ->setMaxResults($itemperpage);

            return $qb->getQuery()
                            ->getResult();
        }
    }
    
    public function findLeadsByStatuByFilterDesc($status, $offset, $itemperpage, $myfilter) {
        if ($myfilter != 'all') {
            $qb = $this->createQueryBuilder('c')
                    ->select('c')
                    ->where('c.status = :status')
                    ->andwhere('c.contacted = :myfilter')
                    ->addOrderBy('c.id', 'DESC')
                    ->setParameters(array('status' => $status, 'myfilter' => $myfilter))
                    ->setFirstResult($offset)
                    ->setMaxResults($itemperpage);

            return $qb->getQuery()
                            ->getResult();
        } else {
            $qb = $this->createQueryBuilder('c')
                    ->select('c')
                    ->where('c.status = :status')
                    ->addOrderBy('c.id', 'DESC')
                    ->setParameters(array('status' => $status))
                    ->setFirstResult($offset)
                    ->setMaxResults($itemperpage);

            return $qb->getQuery()
                            ->getResult();
        }
    }
    
    public function findLeadsStatusMainPage($status, $itemperpage) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.status = :status')
                ->addOrderBy('c.id', 'DESC')
                ->setParameters(array('status' => $status));

        return $qb->getQuery()->setMaxResults($itemperpage)
                        ->getResult();
    }
    
    public function findLeadsStatusNameMainPage($name, $status, $itemperpage) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.status = :status')
                ->andWhere('c.assign = :assign')
                ->addOrderBy('c.id', 'DESC')
                ->setParameters(array('status' => $status, 'assign' => $name,));

        return $qb->getQuery()->setMaxResults($itemperpage)
                        ->getResult();
    }

    public function findLeadsByStatusSortCreatedAsc($status, $offset, $itemperpage, $myfilter) {
        if ($myfilter != 'all') {
            $qb = $this->createQueryBuilder('c')
                    ->select('c')
                    ->where('c.status = :status')
                    ->andwhere('c.contacted = :myfilter')
                    ->addOrderBy('c.lastcontact', 'ASC')
                    ->setParameters(array('status' => $status, 'myfilter' => $myfilter))
                    ->setFirstResult($offset)
                    ->setMaxResults($itemperpage);

            return $qb->getQuery()
                            ->getResult();
        } else {
            $qb = $this->createQueryBuilder('c')
                    ->select('c')
                    ->where('c.status = :status')
                    ->addOrderBy('c.lastcontact', 'ASC')
                    ->setParameters(array('status' => $status))
                    ->setFirstResult($offset)
                    ->setMaxResults($itemperpage);

            return $qb->getQuery()
                            ->getResult();
        }
    }
    
    public function findLeadsByStatusSortCreatedDesc($status, $offset, $itemperpage, $myfilter) {
        if ($myfilter != 'all') {
            $qb = $this->createQueryBuilder('c')
                    ->select('c')
                    ->where('c.status = :status')
                    ->andwhere('c.contacted = :myfilter')
                    ->addOrderBy('c.lastcontact', 'DESC')
                    ->setParameters(array('status' => $status, 'myfilter' => $myfilter))
                    ->setFirstResult($offset)
                    ->setMaxResults($itemperpage);

            return $qb->getQuery()
                            ->getResult();
        } else {
            $qb = $this->createQueryBuilder('c')
                    ->select('c')
                    ->where('c.status = :status')
                    ->addOrderBy('c.lastcontact', 'DESC')
                    ->setParameters(array('status' => $status))
                    ->setFirstResult($offset)
                    ->setMaxResults($itemperpage);

            return $qb->getQuery()
                            ->getResult();
        }
    }

    public function findLeadsByStatusByCreatedCreatedDesc($status, $offset, $itemperpage, $myfilter) {
        if ($myfilter != 'all') {
            $qb = $this->createQueryBuilder('c')
                    ->select('c')
                    ->where('c.status = :status')
                    ->andwhere('c.contacted = :myfilter')
                    ->addOrderBy('c.createdAt', 'DESC')
                    ->setParameters(array('status' => $status, 'myfilter' => $myfilter))
                    ->setFirstResult($offset)
                    ->setMaxResults($itemperpage);

            return $qb->getQuery()
                            ->getResult();
        } else {
            $qb = $this->createQueryBuilder('c')
                    ->select('c')
                    ->where('c.status = :status')
                    ->addOrderBy('c.createdAt', 'DESC')
                    ->setParameters(array('status' => $status))
                    ->setFirstResult($offset)
                    ->setMaxResults($itemperpage);

            return $qb->getQuery()
                            ->getResult();
        }
    }

    public function findLeadsByStatusByCreatedCreatedAsc($status, $offset, $itemperpage, $myfilter) {
        if ($myfilter != 'all') {
            $qb = $this->createQueryBuilder('c')
                    ->select('c')
                    ->where('c.status = :status')
                    ->andwhere('c.contacted = :myfilter')
                    ->addOrderBy('c.createdAt', 'ASC')
                    ->setParameters(array('status' => $status, 'myfilter' => $myfilter))
                    ->setFirstResult($offset)
                    ->setMaxResults($itemperpage);

            return $qb->getQuery()
                            ->getResult();
        } else {
            $qb = $this->createQueryBuilder('c')
                    ->select('c')
                    ->where('c.status = :status')
                    ->addOrderBy('c.createdAt', 'ASC')
                    ->setParameters(array('status' => $status))
                    ->setFirstResult($offset)
                    ->setMaxResults($itemperpage);

            return $qb->getQuery()
                            ->getResult();
        }
    }

    public function findDistinctContacted($status) {

        $qb = $this->createQueryBuilder('c')
                ->select('c.contacted')->distinct()
                ->where('c.status = :status')
                //->addOrderBy('c.id')
                ->setParameters(array('status' => $status));

        return $qb->getQuery()
                        ->getResult();
    }

    public function findmyleadsbystatus($name, $status) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.assign = :assign', 'c.status = :status')
                ->addOrderBy('c.id')
                ->setParameters(array('assign' => $name, 'status' => $status,));

        return $qb->getQuery()
                        ->getResult();
    }

    public function countstatusbyname($name, $status) {

        $qb = $this->createQueryBuilder('c')
                ->select('count(c)')
                ->where('c.assign = :assign', 'c.status = :status')
                ->setParameters(array('assign' => $name, 'status' => $status,));

        return $qb->getQuery()
                        ->getResult();
    }

    public function counttodobyname($name) {
        $status = array('new', 'In progress');
        $qb = $this->createQueryBuilder('c')
                ->select('count(c)')
                ->where('c.assign = :assign', 'c.status IN (:status)')
                ->setParameters(array('assign' => $name, 'status' => $status));

        return $qb->getQuery()
                        ->getSingleScalarResult();
    }

    public function countstatus($status) {

        $qb = $this->createQueryBuilder('c')
                ->select('count(c)')
                ->where('c.status = :status')
                ->setParameters(array('status' => $status,));

        return $qb->getQuery()
                        ->getSingleScalarResult();
    }

    public function countStatusByFilter($status, $myfilter) {
        if ($myfilter != 'all') {
            $qb = $this->createQueryBuilder('c')
                    ->select('count(c)')
                    ->where('c.status = :status')
                    ->andWhere('c.contacted = :contacted')
                    ->setParameters(array('status' => $status, 'contacted' => $myfilter));

            return $qb->getQuery()
                            ->getResult();
        } else {
            $qb = $this->createQueryBuilder('c')
                    ->select('count(c)')
                    ->where('c.status = :status')
                    ->setParameters(array('status' => $status,));

            return $qb->getQuery()
                            ->getResult();
        }
    }
    
    public function countflag() {

        $qb = $this->createQueryBuilder('c')
                ->select('count(c)')
                ->where('c.flag = :flag')
                ->setParameters(array('flag' => '1',));

        return $qb->getQuery()
                        ->getSingleScalarResult();
    }

    public function countall() {

        $qb = $this->createQueryBuilder('c')
                ->select('count(c)');

        return $qb->getQuery()
                        ->getSingleScalarResult();
    }

    public function countstatusbynamethismonth($name, $status) {
        $thismonthdate = new \DateTime('midnight first day of this month');
        $qb = $this->createQueryBuilder('c')
                ->select('count(c)')
                ->where('c.assign = :assign', 'c.status = :status', 'c.wondate > :date')
                ->setParameters(array('assign' => $name, 'status' => $status, 'date' => $thismonthdate,));

        return $qb->getQuery()
                        ->getSingleScalarResult();
    }

    public function countstatusthismonth($status) {
        $thismonthdate = new \DateTime('midnight first day of this month');
        $qb = $this->createQueryBuilder('c')
                ->select('count(c)')
                ->where('c.status = :status', 'c.wondate > :date')
                ->setParameters(array('status' => $status, 'date' => $thismonthdate,));

        return $qb->getQuery()
                        ->getSingleScalarResult();
    }

    public function countwonthismonthbyname($name, $status) {
        $thismonthdate = new \DateTime('midnight first day of this month');
        $qb = $this->createQueryBuilder('c')
                ->select('count(c)')
                ->where('c.wondate is NOT NULL', 'c.assign = :assign', 'c.wondate > :date', 'c.status = :status')
                ->setParameters(array('assign' => $name, 'date' => $thismonthdate, 'status' => $status));

        return $qb->getQuery()
                        ->getSingleScalarResult();
    }

    public function findLeadsByName($name) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.customerName = :customerName')
                ->addOrderBy('c.id')
                ->setParameters(array('customerName' => $name));

        return $qb->getQuery()
                        ->getResult();
    }

    public function findLeadsByEmail($email) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.customerEmail = :customerEmail')
                ->addOrderBy('c.id')
                ->setParameters(array('customerEmail' => $email));

        return $qb->getQuery()
                        ->getResult();
    }

    public function findLeadsByTell($tel) {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.customerTel = :customerTel')
                ->addOrderBy('c.id')
                ->setParameters(array('customerTel' => $tel));

        return $qb->getQuery()
                        ->getResult();
    }

    public function findmytodobyname($name) {
        $status = array('new', 'In progress');
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.assign = :assign', 'c.status IN (:status)')
                ->setParameters(array('assign' => $name, 'status' => $status));

        return $qb->getQuery()
                        ->getResult();
    }

    public function searchLeadsByName($name) {

        $qb = $this->createQueryBuilder('n');
        $result = $qb->select('n')
                ->where($qb->expr()->like('n.customerName', $qb->expr()->literal('%' . $name . '%')))
                ->getQuery()
                ->getResult();
        return $result;
    }

    public function searchLeadsByPhone($phone) {

        $qb = $this->createQueryBuilder('n');
        $result = $qb->select('n')
                ->where($qb->expr()->like('n.customerTel', $qb->expr()->literal('%' . $phone . '%')))
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

    public function countstatusbynamebysmonth($name, $status, $month) {
        $thismonthdate = new \DateTime('midnight first day of this month');
        $qb = $this->createQueryBuilder('c')
                ->select('count(c)')
                ->where('c.assign = :assign', 'c.status = :status', 'c.wondate > :date')
                ->setParameters(array('assign' => $name, 'status' => $status, 'date' => $thismonthdate,));

        return $qb->getQuery()
                        ->getSingleScalarResult();
    }

    public function findYearMonthName($name, $year, $month) {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');

        $qb = $this->createQueryBuilder('p');
        $qb->select('p')
                ->where('YEAR(p.wondate) = :year')
                ->andWhere('MONTH(p.wondate) = :month')
                ->andWhere('p.assign = :assign');

        $qb->setParameter('year', $year)
                ->setParameter('month', $month)
                ->setParameter('assign', $name);



        return $qb->getQuery()
                        ->getResult();
    }

    public function findallreminder() {

        $qb = $this->createQueryBuilder('u')
                ->where('u.reminder IS NOT NULL');

        return $qb->getQuery()
                        ->getResult();
    }

    public function findalertreminder() {

        $qb = $this->createQueryBuilder('u')
                ->where('u.reminder <= :today')
                ->setParameters(array('today' => new \DateTime()));
        ;

        return $qb->getQuery()
                        ->getResult();
    }

    public function countalertreminder() {

        $qb = $this->createQueryBuilder('u')
                ->select('count(u)')
                ->where('u.reminder <= :today')
                ->setParameters(array('today' => new \DateTime()));
        ;

        return $qb->getQuery()
                        ->getResult();
    }

    public function findLeadsByDateStatus($status, $datefrom, $dateto) {
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.status = :status', 'c.createdAt >= :date1', 'c.createdAt <= :date2')
                ->addOrderBy('c.id')
                //->setMaxResults($limit)
                ->setParameters(array('status' => $status, 'date1' => $datefrom, 'date2' => $dateto,));
        return $qb->getQuery()
                        ->getResult();
    }

    public function findLeadsByDateStatusCount($status, $datefrom, $dateto) {
        if ($status === 'all') {
            $qb = $this->createQueryBuilder('c')
                    ->select('count(c)')
                    ->where('c.createdAt >= :date1', 'c.createdAt <= :date2')
                    ->addOrderBy('c.id')
                    ->setParameters(array('date1' => $datefrom, 'date2' => $dateto,));
            return $qb->getQuery()
                            ->getSingleScalarResult();
        } else {
            $qb = $this->createQueryBuilder('c')
                    ->select('count(c)')
                    ->where('c.status = :status', 'c.createdAt >= :date1', 'c.createdAt <= :date2')
                    ->addOrderBy('c.id')
                    ->setParameters(array('status' => $status, 'date1' => $datefrom, 'date2' => $dateto,));
            return $qb->getQuery()
                            ->getSingleScalarResult();
        }
    }
    
    public function findLeadsByDateNotReachedCount($datefrom, $dateto) {
            $qb = $this->createQueryBuilder('c')
                    ->select('count(c)')
                    ->where('c.contacted = :contacted', 'c.status = :status', 'c.createdAt >= :date1', 'c.createdAt <= :date2')
                    ->addOrderBy('c.id')
                    ->setParameters(array('contacted'=> '0', 'date1' => $datefrom, 'date2' => $dateto, 'status' => 'In progress'));
            return $qb->getQuery()
                            ->getSingleScalarResult();
    }
    
    

    public function findCreatedAtLimit($limit, $offset) {
        $qb = $this->createQueryBuilder('c')
                ->select('c.createdAt')
                ->setFirstResult($offset)
                ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    public function countAllLead() {
        $qb = $this->createQueryBuilder('c')
                ->select('count(c)');
        return $qb->getQuery()->getSingleScalarResult();
    }
    
    public function findnullcontacted() {

        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.id')
                ->where('c.contacted is NULL');
                //->setParameters(array('contacted' => null));

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function createmaincontact() {
        $fields = array('c.id', 'c.customerEmail', 'c.customerName');
        $qb = $this->createQueryBuilder('c')
                ->select($fields)
                ->addOrderBy('c.id');

        return $qb->getQuery()
                        ->getResult();
    }
    
    public function checkPhoneNumberTextMessage($checkNumber) {
        $qb = $this->createQueryBuilder('n');
        $result = $qb->select('n.id', 'n.customerName')
                ->where($qb->expr()->like('n.customerTel', $qb->expr()->literal('%' . $checkNumber . '%')))
                ->setMaxResults(1)
                ->getQuery()
                ->getResult();
        return $result;
    }
    
    public function findTempLead($limit, $counter) {
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.id', 'ASC')
                ->setFirstResult($counter)
                ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
    
    

}
