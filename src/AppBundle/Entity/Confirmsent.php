<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Confirmsent
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ConfirmsentRepository")
 */
class Confirmsent
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
     * @ORM\Column(name="user", type="string", length=255)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="CustomerName", type="string", length=255)
     */
    private $customerName;

    /**
     * @var string
     *
     * @ORM\Column(name="CustomerEmail", type="string", length=255)
     */
    private $customerEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="FromEmail", type="string", length=255)
     */
    private $fromEmail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreatedAt", type="datetime")
     */
    private $createdAt;
    
    /**
     * @var \string
     *
     * @ORM\Column(name="appDate", type="string", nullable=true)
     */
    private $appDate;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var integer
     *
     * @ORM\Column(name="Applength", type="integer", nullable=true)
     */
    private $applength;

    /**
     * @var string
     *
     * @ORM\Column(name="Doctor", type="string", length=255)
     */
    private $doctor;

    /**
     * @var integer
     *
     * @ORM\Column(name="TempId", type="integer")
     */
    private $tempId;

    /**
     * @var integer
     *
     * @ORM\Column(name="LeadId", type="integer", nullable=true)
     */
    private $leadId;

    /**
     * @var integer
     *
     * @ORM\Column(name="LogId", type="integer", nullable=true)
     */
    private $logId;


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
     * Set user
     *
     * @param string $user
     *
     * @return Confirmsent
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set customerName
     *
     * @param string $customerName
     *
     * @return Confirmsent
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
     * Set customerEmail
     *
     * @param string $customerEmail
     *
     * @return Confirmsent
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;
    
        return $this;
    }

    /**
     * Get customerEmail
     *
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * Set fromEmail
     *
     * @param string $fromEmail
     *
     * @return Confirmsent
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;
    
        return $this;
    }

    /**
     * Get fromEmail
     *
     * @return string
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Confirmsent
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
     * Set body
     *
     * @param string $body
     *
     * @return Confirmsent
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
     * Set applength
     *
     * @param integer $applength
     *
     * @return Confirmsent
     */
    public function setApplength($applength)
    {
        $this->applength = $applength;
    
        return $this;
    }

    /**
     * Get applength
     *
     * @return integer
     */
    public function getApplength()
    {
        return $this->applength;
    }

    /**
     * Set doctor
     *
     * @param string $doctor
     *
     * @return Confirmsent
     */
    public function setDoctor($doctor)
    {
        $this->doctor = $doctor;
    
        return $this;
    }

    /**
     * Get doctor
     *
     * @return string
     */
    public function getDoctor()
    {
        return $this->doctor;
    }

    /**
     * Set tempId
     *
     * @param string $tempId
     *
     * @return Confirmsent
     */
    public function setTempId($tempId)
    {
        $this->tempId = $tempId;
    
        return $this;
    }

    /**
     * Get tempId
     *
     * @return string
     */
    public function getTempId()
    {
        return $this->tempId;
    }

    /**
     * Set leadId
     *
     * @param integer $leadId
     *
     * @return Confirmsent
     */
    public function setLeadId($leadId)
    {
        $this->leadId = $leadId;
    
        return $this;
    }

    /**
     * Get leadId
     *
     * @return integer
     */
    public function getLeadId()
    {
        return $this->leadId;
    }

    /**
     * Set logId
     *
     * @param integer $logId
     *
     * @return Confirmsent
     */
    public function setLogId($logId)
    {
        $this->logId = $logId;
    
        return $this;
    }

    /**
     * Get logId
     *
     * @return integer
     */
    public function getLogId()
    {
        return $this->logId;
    }

    /**
     * Set appDate
     *
     * @param \DateTime $appDate
     *
     * @return Confirmsent
     */
    public function setAppDate($appDate)
    {
        $this->appDate = $appDate;
    
        return $this;
    }

    /**
     * Get appDate
     *
     * @return \DateTime
     */
    public function getAppDate()
    {
        return $this->appDate;
    }
}
