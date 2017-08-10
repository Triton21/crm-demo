<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Options
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\OptionsRepository")
 */
class Options
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
     * @ORM\OneToMany(targetEntity="Visit", mappedBy="options")
     */
    protected $visits;

    public function __construct()
    {
        $this->visits = new ArrayCollection();
    }
    
    /**
     * @var integer
     *
     * @ORM\Column(name="tpid", type="integer")
     */
    private $tpid;

    /**
     * @var string
     *
     * @ORM\Column(name="optionName", type="string", length=255)
     */
    private $optionName;

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
     * Set tpid
     *
     * @param integer $tpid
     *
     * @return Options
     */
    public function setTpid($tpid)
    {
        $this->tpid = $tpid;
    
        return $this;
    }

    /**
     * Get tpid
     *
     * @return integer
     */
    public function getTpid()
    {
        return $this->tpid;
    }

    /**
     * Set optionName
     *
     * @param string $optionName
     *
     * @return Options
     */
    public function setOptionName($optionName)
    {
        $this->optionName = $optionName;
    
        return $this;
    }

    /**
     * Get optionName
     *
     * @return string
     */
    public function getOptionName()
    {
        return $this->optionName;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Options
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

