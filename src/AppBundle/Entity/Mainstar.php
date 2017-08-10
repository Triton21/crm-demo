<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mainstar
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MainstarRepository")
 */
class Mainstar
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
     * @ORM\Column(name="inboxid", type="integer", nullable=true)
     */
    private $inboxid;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="settid", type="integer", nullable=true)
     */
    private $settid;

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
     * Set inboxid
     *
     * @param integer $inboxid
     *
     * @return Mainstar
     */
    public function setInboxid($inboxid)
    {
        $this->inboxid = $inboxid;
    
        return $this;
    }

    /**
     * Get inboxid
     *
     * @return integer
     */
    public function getInboxid()
    {
        return $this->inboxid;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Mainstar
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
     * Set settid
     *
     * @param integer $settid
     *
     * @return Mainstar
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
}
