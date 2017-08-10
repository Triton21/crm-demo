<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ctemplate
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CtemplateRepository")
 * @ORM\HasLifecycleCallbacks() 
 */
class Ctemplate {

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
     * @ORM\Column(name="tempname", type="string", length=255)
     */
    private $tempname;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", length=21000)
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="attach1", type="integer", nullable = true)
     */
    private $attach1;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="attach2", type="integer", nullable = true)
     */
    private $attach2;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="attach3", type="integer", nullable = true)
     */
    private $attach3;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * Get getupdatedAt
     *
     * @return \DateTime
     */
    public function getupdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Set updatedAt 
     * 
     * @ORM\PreUpdate 
     */
    public function setUpdatedAt() {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set tempname
     *
     * @param string $tempname
     *
     * @return Etemplate
     */
    public function setTempname($tempname) {
        $this->tempname = $tempname;

        return $this;
    }

    /**
     * Get tempname
     *
     * @return string
     */
    public function getTempname() {
        return $this->tempname;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Etemplate
     */
    public function setSubject($subject) {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Etemplate
     */
    public function setBody($body) {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Etemplate
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }


    /**
     * Set attach1
     *
     * @param integer $attach1
     *
     * @return Ctemplate
     */
    public function setAttach1($attach1)
    {
        $this->attach1 = $attach1;
    
        return $this;
    }

    /**
     * Get attach1
     *
     * @return integer
     */
    public function getAttach1()
    {
        return $this->attach1;
    }

    /**
     * Set attach2
     *
     * @param integer $attach2
     *
     * @return Ctemplate
     */
    public function setAttach2($attach2)
    {
        $this->attach2 = $attach2;
    
        return $this;
    }

    /**
     * Get attach2
     *
     * @return integer
     */
    public function getAttach2()
    {
        return $this->attach2;
    }

    /**
     * Set attach3
     *
     * @param integer $attach3
     *
     * @return Ctemplate
     */
    public function setAttach3($attach3)
    {
        $this->attach3 = $attach3;
    
        return $this;
    }

    /**
     * Get attach3
     *
     * @return integer
     */
    public function getAttach3()
    {
        return $this->attach3;
    }
}
