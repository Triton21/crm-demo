<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usermonitor
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UsermonitorRepository")
 */
class Usermonitor
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="logtype", type="string", length=255)
     */
    private $logtype;
    
    /**
     * @var string
     *
     * @ORM\Column(name="userip", type="string", length=255, nullable=true)
     */
    private $userip;
    
    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="userId", type="integer")
     */
    private $userId;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set logtype
     *
     * @param string $logtype
     *
     * @return Usermonitor
     */
    public function setLogtype($logtype)
    {
        $this->logtype = $logtype;
    
        return $this;
    }

    /**
     * Get logtype
     *
     * @return string
     */
    public function getLogtype()
    {
        return $this->logtype;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Usermonitor
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Usermonitor
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Usermonitor
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set userip
     *
     * @param string $userip
     *
     * @return Usermonitor
     */
    public function setUserip($userip)
    {
        $this->userip = $userip;
    
        return $this;
    }

    /**
     * Get userip
     *
     * @return string
     */
    public function getUserip()
    {
        return $this->userip;
    }
}
