<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Signature
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SignatureRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Signature
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
     * @var string
     *
     * @ORM\Column(name="texthtml", type="text")
     */
    private $texthtml;

    /**
     * @var integer
     *
     * @ORM\Column(name="settid", type="integer")
     */
    private $settid;


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
     * @return Signature
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
     * @return Signature
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
     * Set texthtml
     *
     * @param string $texthtml
     *
     * @return Signature
     */
    public function setTexthtml($texthtml)
    {
        $this->texthtml = $texthtml;
    
        return $this;
    }

    /**
     * Get texthtml
     *
     * @return string
     */
    public function getTexthtml()
    {
        return $this->texthtml;
    }
}
