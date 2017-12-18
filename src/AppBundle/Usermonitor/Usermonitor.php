<?php

namespace AppBundle\Usermonitor;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Userlog;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Usermonitor {
    
    /**
     *
     * @var EntityManager 
     */
    protected $em;
    
    /*
     * @var current user
     */
    private $user;

    public function __construct(EntityManager $entityManager, TokenStorageInterface $tokenStorage) {
        $this->em = $entityManager;
        $this->user = $tokenStorage->getToken()->getUser();
    }
    
    public function register($register) {
        $name = $this->user->getUsername();
        $userId = $this->user->getId();
        $em = $this->em;
        $userlog = new Userlog();
        $userlog->setUsername($name);
        $userlog->setRegister($register);
        $userlog->setCreatedAt(new \DateTime());
        $em->persist($userlog);
        $em->flush();
        return true;
    }
    
    
}