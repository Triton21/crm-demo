<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inbox
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\InboxRepository")
 * @ORM\HasLifecycleCallbacks() 
 */
class Inbox {

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
     * @ORM\Column(name="fromemail", type="string", length=255)
     */
    private $fromemail;

    /**
     * @var string
     *
     * @ORM\Column(name="mailid", type="string", length=255)
     */
    private $mailid;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=6444, nullable=true)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;
    
    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    private $source;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="maildate", type="string", length=255)
     */
    private $maildate;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set fromemail
     *
     * @param string $fromemail
     *
     * @return Inbox
     */
    public function setFromemail($fromemail) {
        $this->fromemail = $fromemail;

        return $this;
    }

    /**
     * Get fromemail
     *
     * @return string
     */
    public function getFromemail() {
        return $this->fromemail;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Inbox
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
     * Set content
     *
     * @param string $content
     *
     * @return Inbox
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Inbox
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @ORM\PreUpdate
     */
    public function setCreatedAt() {
        $this->createdAt = new \DateTime();
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set mailid
     *
     * @param string $mailid
     *
     * @return Inbox
     */
    public function setMailid($mailid) {
        $this->mailid = $mailid;

        return $this;
    }

    /**
     * Get mailid
     *
     * @return string
     */
    public function getMailid() {
        return $this->mailid;
    }


    /**
     * Set maildate
     *
     * @param string $maildate
     *
     * @return Inbox
     */
    public function setMaildate($maildate)
    {
        $this->maildate = $maildate;

        return $this;
    }

    /**
     * Get maildate
     *
     * @return @return string
     */
    public function getMaildate()
    {
        return $this->maildate;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return Inbox
     */
    public function setSource($source)
    {
        $this->source = $source;
    
        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }
}
