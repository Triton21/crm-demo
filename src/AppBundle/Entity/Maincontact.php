<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Maincontact
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MaincontactRepository")
 */
class Maincontact
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
     * @ORM\Column(name="name", type="text")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="leadid", type="integer", nullable=true)
     */
    private $leadid;


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
     * Set name
     *
     * @param string $name
     *
     * @return Maincontact
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Maincontact
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set leadid
     *
     * @param integer $leadid
     *
     * @return Maincontact
     */
    public function setLeadid($leadid)
    {
        $this->leadid = $leadid;
    
        return $this;
    }

    /**
     * Get leadid
     *
     * @return integer
     */
    public function getLeadid()
    {
        return $this->leadid;
    }
}

