<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mainmessagerules
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MainmessagerulesRepository")
 */
class Mainmessagerules
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
     * @var string
     *
     * @ORM\Column(name="rulename", type="string", length=255)
     */
    private $rulename;

    /**
     * @var integer
     *
     * @ORM\Column(name="settid", type="integer")
     */
    private $settid;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    
    /**
     * @var string
     *
     * @ORM\Column(name="folder", type="string", length=255)
     */
    private $folder;

    /**
     * @var string
     *
     * @ORM\Column(name="filtertext", type="string", length=255)
     */
    private $filtertext;


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
     * Set username
     *
     * @param string $username
     *
     * @return Mainmessagerules
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Mainmessagerules
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
     * Set rulename
     *
     * @param string $rulename
     *
     * @return Mainmessagerules
     */
    public function setRulename($rulename)
    {
        $this->rulename = $rulename;
    
        return $this;
    }

    /**
     * Get rulename
     *
     * @return string
     */
    public function getRulename()
    {
        return $this->rulename;
    }

    /**
     * Set settid
     *
     * @param integer $settid
     *
     * @return Mainmessagerules
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
     * Set type
     *
     * @param string $type
     *
     * @return Mainmessagerules
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set filtertext
     *
     * @param string $filtertext
     *
     * @return Mainmessagerules
     */
    public function setFiltertext($filtertext)
    {
        $this->filtertext = $filtertext;
    
        return $this;
    }

    /**
     * Get filtertext
     *
     * @return string
     */
    public function getFiltertext()
    {
        return $this->filtertext;
    }

    /**
     * Set folder
     *
     * @param string $folder
     *
     * @return Mainmessagerules
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
    
        return $this;
    }

    /**
     * Get folder
     *
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }
}
