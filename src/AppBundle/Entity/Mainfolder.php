<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mainfolder
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MainfolderRepository")
 */
class Mainfolder
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
     * @var integer
     *
     * @ORM\Column(name="settid", type="integer")
     */
    private $settid;

    /**
     * @var string
     *
     * @ORM\Column(name="foldername", type="string", length=255)
     */
    private $foldername;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;


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
     * Set settid
     *
     * @param integer $settid
     *
     * @return Mainfolder
     */
    public function setSettid($settid)
    {
        $this->settid = $settid;
    
        return $this;
    }

    /**
     * Get settid
     *
     * @return integer
     */
    public function getSettid()
    {
        return $this->settid;
    }

    /**
     * Set foldername
     *
     * @param string $foldername
     *
     * @return Mainfolder
     */
    public function setFoldername($foldername)
    {
        $this->foldername = $foldername;
    
        return $this;
    }

    /**
     * Get foldername
     *
     * @return string
     */
    public function getFoldername()
    {
        return $this->foldername;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Mainfolder
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
     * Set username
     *
     * @param string $username
     *
     * @return Mainfolder
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
}

