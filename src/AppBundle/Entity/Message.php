<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MessageRepository")
 */
class Message
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
     * @ORM\Column(name="customerName", type="string", length=255, nullable=true)
     */
    private $customerName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deadline", type="datetime", nullable=true)
     */
    private $deadline;

    /**
     * @var integer
     *
     * @ORM\Column(name="leadid", type="integer", nullable=true)
     */
    private $leadid;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="sendmail", type="integer", nullable=true)
     */
    private $sendmail;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    private $priority;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="sendemail", type="integer", nullable=true)
     */
    private $sendemail;

    /**
     * @var string
     *
     * @ORM\Column(name="logid", type="integer", nullable=true)
     */
    private $logid;

    /**
     * @var string
     *
     * @ORM\Column(name="assign", type="string", length=255)
     */
    private $assign;

    /**
     * @var integer
     *
     * @ORM\Column(name="unread", type="smallint", nullable=true)
     */
    private $unread;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="text")
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;


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
     * @return Message
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
     * @return Message
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
     * Set leadid
     *
     * @param string $leadid
     *
     * @return Message
     */
    public function setLeadid($leadid)
    {
        $this->leadid = $leadid;
    
        return $this;
    }

    /**
     * Get leadid
     *
     * @return string
     */
    public function getLeadid()
    {
        return $this->leadid;
    }

    /**
     * Set logid
     *
     * @param string $logid
     *
     * @return Message
     */
    public function setLogid($logid)
    {
        $this->logid = $logid;
    
        return $this;
    }

    /**
     * Get logid
     *
     * @return string
     */
    public function getLogid()
    {
        return $this->logid;
    }

    /**
     * Set assign
     *
     * @param string $assign
     *
     * @return Message
     */
    public function setAssign($assign)
    {
        $this->assign = $assign;
    
        return $this;
    }

    /**
     * Get assign
     *
     * @return string
     */
    public function getAssign()
    {
        return $this->assign;
    }

    /**
     * Set unread
     *
     * @param integer $unread
     *
     * @return Message
     */
    public function setUnread($unread)
    {
        $this->unread = $unread;
    
        return $this;
    }

    /**
     * Get unread
     *
     * @return integer
     */
    public function getUnread()
    {
        return $this->unread;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Message
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Message
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     *
     * @return Message
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    
        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set sendemail
     *
     * @param integer $sendemail
     *
     * @return Message
     */
    public function setSendemail($sendemail)
    {
        $this->sendemail = $sendemail;
    
        return $this;
    }

    /**
     * Get sendemail
     *
     * @return integer
     */
    public function getSendemail()
    {
        return $this->sendemail;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     *
     * @return Message
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    
        return $this;
    }

    /**
     * Get priority
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set customerName
     *
     * @param string $customerName
     *
     * @return Message
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    
        return $this;
    }

    /**
     * Get customerName
     *
     * @return string
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * Set sendmail
     *
     * @param integer $sendmail
     *
     * @return Message
     */
    public function setSendmail($sendmail)
    {
        $this->sendmail = $sendmail;
    
        return $this;
    }

    /**
     * Get sendmail
     *
     * @return integer
     */
    public function getSendmail()
    {
        return $this->sendmail;
    }
}
