<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Campaign
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CampaignRepository")
 */
class Campaign {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Elist", mappedBy="campaign", cascade={"persist", "remove"})
     */
    protected $elists;

    public function __construct() {
        $this->elists = new ArrayCollection();
    }

    /**
     * @var string
     *
     * @ORM\Column(name="listname", type="string", length=255)
     */
    private $listname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="date")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date1", type="date", nullable=true)
     */
    private $date1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date2", type="date", nullable=true)
     */
    private $date2;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="active", type="string", length=255)
     */
    private $active;

    /**
     * @var integer
     *
     * @ORM\Column(name="shift", type="integer")
     */
    private $shift;

    /**
     * @var integer
     *
     * @ORM\Column(name="leadnum", type="integer", nullable=true)
     */
    private $leadnum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastsent", type="date", nullable=true)
     */
    private $lastsent;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=255, nullable=true)
     */
    private $template;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=255)
     */
    private $user;

    

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
     * Set listname
     *
     * @param string $listname
     *
     * @return Campaign
     */
    public function setListname($listname)
    {
        $this->listname = $listname;
    
        return $this;
    }

    /**
     * Get listname
     *
     * @return string
     */
    public function getListname()
    {
        return $this->listname;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Campaign
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
     * Set date1
     *
     * @param \DateTime $date1
     *
     * @return Campaign
     */
    public function setDate1($date1)
    {
        $this->date1 = $date1;
    
        return $this;
    }

    /**
     * Get date1
     *
     * @return \DateTime
     */
    public function getDate1()
    {
        return $this->date1;
    }

    /**
     * Set date2
     *
     * @param \DateTime $date2
     *
     * @return Campaign
     */
    public function setDate2($date2)
    {
        $this->date2 = $date2;
    
        return $this;
    }

    /**
     * Get date2
     *
     * @return \DateTime
     */
    public function getDate2()
    {
        return $this->date2;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Campaign
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
     * @return Campaign
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
     * Set shift
     *
     * @param integer $shift
     *
     * @return Campaign
     */
    public function setShift($shift)
    {
        $this->shift = $shift;
    
        return $this;
    }

    /**
     * Get shift
     *
     * @return integer
     */
    public function getShift()
    {
        return $this->shift;
    }

    /**
     * Set leadnum
     *
     * @param integer $leadnum
     *
     * @return Campaign
     */
    public function setLeadnum($leadnum)
    {
        $this->leadnum = $leadnum;
    
        return $this;
    }

    /**
     * Get leadnum
     *
     * @return integer
     */
    public function getLeadnum()
    {
        return $this->leadnum;
    }

    /**
     * Set lastsent
     *
     * @param \DateTime $lastsent
     *
     * @return Campaign
     */
    public function setLastsent($lastsent)
    {
        $this->lastsent = $lastsent;
    
        return $this;
    }

    /**
     * Get lastsent
     *
     * @return \DateTime
     */
    public function getLastsent()
    {
        return $this->lastsent;
    }

    /**
     * Set template
     *
     * @param string $template
     *
     * @return Campaign
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    
        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return Campaign
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
     * Add elist
     *
     * @param \AppBundle\Entity\Elist $elist
     *
     * @return Campaign
     */
    public function addElist(\AppBundle\Entity\Elist $elist)
    {
        $this->elists[] = $elist;
    
        return $this;
    }

    /**
     * Remove elist
     *
     * @param \AppBundle\Entity\Elist $elist
     */
    public function removeElist(\AppBundle\Entity\Elist $elist)
    {
        $this->elists->removeElement($elist);
    }

    /**
     * Get elists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElists()
    {
        return $this->elists;
    }
}
