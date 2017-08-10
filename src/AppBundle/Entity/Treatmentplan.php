<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Treatmentplan
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TreatmentplanRepository")
 */
class Treatmentplan
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
     * @ORM\Column(name="customerName", type="string", length=255)
     */
    private $customerName;

    /**
     * @var string
     *
     * @ORM\Column(name="customerEmail", type="string", length=255)
     */
    private $customerEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="customerTel", type="string", length=255)
     */
    private $customerTel;

    /**
     * @var integer
     *
     * @ORM\Column(name="leadid", type="integer")
     */
    private $leadid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;


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
     * Set customerName
     *
     * @param string $customerName
     *
     * @return Treatmentplan
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
     * @return Treatmentplan
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
     * Set customerTel
     *
     * @param string $customerTel
     *
     * @return Treatmentplan
     */
    public function setCustomerTel($customerTel)
    {
        $this->customerTel = $customerTel;
    
        return $this;
    }

    /**
     * Get customerTel
     *
     * @return string
     */
    public function getCustomerTel()
    {
        return $this->customerTel;
    }

    /**
     * Set leadid
     *
     * @param integer $leadid
     *
     * @return Treatmentplan
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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Treatmentplan
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
}

