<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Elist
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ElistRepository")
 */
class Elist
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
     * @ORM\OneToMany(targetEntity="Esent", mappedBy="elist", cascade={"persist", "remove"})
     */
    protected $esents;
    
    public function __construct()
    {
        $this->esents = new ArrayCollection();
    }
          
    /**
     * @ORM\ManyToOne(targetEntity="Campaign", inversedBy="elists")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id")
     */
    protected $campaign;

    /**
     * @var string
     *
     * @ORM\Column(name="CustomerName", type="string", length=255, nullable=true)
     */
    private $customerName;

    /**
     * @var string
     *
     * @ORM\Column(name="FirstName", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="LastName", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=255)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreatedAt", type="date")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="active", type="string", length=255)
     */
    private $active;
    
    /**
     * @var string
     *
     * @ORM\Column(name="leadId", type="string", length=255, nullable = true)
     */
    private $leadId;

    

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
     * @return Elist
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Elist
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Elist
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Elist
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Elist
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
     * Set status
     *
     * @param string $status
     *
     * @return Elist
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set active
     *
     * @param string $active
     *
     * @return Elist
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set campaign
     *
     * @param \AppBundle\Entity\Campaign $campaign
     *
     * @return Elist
     */
    public function setCampaign(\AppBundle\Entity\Campaign $campaign = null)
    {
        $this->campaign = $campaign;
    
        return $this;
    }

    /**
     * Get campaign
     *
     * @return \AppBundle\Entity\Campaign
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Set leadId
     *
     * @param string $leadId
     *
     * @return Elist
     */
    public function setLeadId($leadId)
    {
        $this->leadId = $leadId;
    
        return $this;
    }

    /**
     * Get leadId
     *
     * @return string
     */
    public function getLeadId()
    {
        return $this->leadId;
    }

    /**
     * Add esent
     *
     * @param \AppBundle\Entity\Esent $esent
     *
     * @return Elist
     */
    public function addEsent(\AppBundle\Entity\Esent $esent)
    {
        $this->esents[] = $esent;
    
        return $this;
    }

    /**
     * Remove esent
     *
     * @param \AppBundle\Entity\Esent $esent
     */
    public function removeEsent(\AppBundle\Entity\Esent $esent)
    {
        $this->esents->removeElement($esent);
    }

    /**
     * Get esents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEsents()
    {
        return $this->esents;
    }
}
