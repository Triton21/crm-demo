<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Visit
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\VisitRepository")
 */
class Visit
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
     * @ORM\ManyToOne(targetEntity="Options", inversedBy="visits")
     * @ORM\JoinColumn(name="options_id", referencedColumnName="id")
     */
    protected $options;
    

    /**
     * @var integer
     *
     * @ORM\Column(name="optionid", type="integer", nullable=true)
     */
    private $optionid;

    /**
     * @var string
     *
     * @ORM\Column(name="visitName", type="string", length=255)
     */
    private $visitName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="AR8", type="string", length=255)
     */
    private $aR8;

    /**
     * @var string
     *
     * @ORM\Column(name="AR7", type="string", length=255)
     */
    private $aR7;

    /**
     * @var string
     *
     * @ORM\Column(name="AR6", type="string", length=255)
     */
    private $aR6;

    /**
     * @var string
     *
     * @ORM\Column(name="AR5", type="string", length=255)
     */
    private $aR5;

    /**
     * @var string
     *
     * @ORM\Column(name="AR4", type="string", length=255)
     */
    private $aR4;

    /**
     * @var string
     *
     * @ORM\Column(name="AR3", type="string", length=255)
     */
    private $aR3;

    /**
     * @var string
     *
     * @ORM\Column(name="AR2", type="string", length=255)
     */
    private $aR2;

    /**
     * @var string
     *
     * @ORM\Column(name="AR1", type="string", length=255)
     */
    private $aR1;

    /**
     * @var string
     *
     * @ORM\Column(name="AL1", type="string", length=255)
     */
    private $aL1;

    /**
     * @var string
     *
     * @ORM\Column(name="Al2", type="string", length=255)
     */
    private $al2;

    /**
     * @var string
     *
     * @ORM\Column(name="AL3", type="string", length=255)
     */
    private $aL3;

    /**
     * @var string
     *
     * @ORM\Column(name="AL4", type="string", length=255)
     */
    private $aL4;

    /**
     * @var string
     *
     * @ORM\Column(name="AL5", type="string", length=255)
     */
    private $aL5;

    /**
     * @var string
     *
     * @ORM\Column(name="AL6", type="string", length=255)
     */
    private $aL6;

    /**
     * @var string
     *
     * @ORM\Column(name="AL7", type="string", length=255)
     */
    private $aL7;

    /**
     * @var string
     *
     * @ORM\Column(name="AL8", type="string", length=255)
     */
    private $aL8;

    /**
     * @var string
     *
     * @ORM\Column(name="BR1", type="string", length=255)
     */
    private $bR1;

    /**
     * @var string
     *
     * @ORM\Column(name="BR2", type="string", length=255)
     */
    private $bR2;

    /**
     * @var string
     *
     * @ORM\Column(name="BR3", type="string", length=255)
     */
    private $bR3;

    /**
     * @var string
     *
     * @ORM\Column(name="BR4", type="string", length=255)
     */
    private $bR4;

    /**
     * @var string
     *
     * @ORM\Column(name="BR5", type="string", length=255)
     */
    private $bR5;

    /**
     * @var string
     *
     * @ORM\Column(name="BR6", type="string", length=255)
     */
    private $bR6;

    /**
     * @var string
     *
     * @ORM\Column(name="BR7", type="string", length=255)
     */
    private $bR7;

    /**
     * @var string
     *
     * @ORM\Column(name="BR8", type="string", length=255)
     */
    private $bR8;

    /**
     * @var string
     *
     * @ORM\Column(name="BL1", type="string", length=255)
     */
    private $bL1;

    /**
     * @var string
     *
     * @ORM\Column(name="BL2", type="string", length=255)
     */
    private $bL2;

    /**
     * @var string
     *
     * @ORM\Column(name="BL3", type="string", length=255)
     */
    private $bL3;

    /**
     * @var string
     *
     * @ORM\Column(name="BL4", type="string", length=255)
     */
    private $bL4;

    /**
     * @var string
     *
     * @ORM\Column(name="BL5", type="string", length=255)
     */
    private $bL5;

    /**
     * @var string
     *
     * @ORM\Column(name="BL6", type="string", length=255)
     */
    private $bL6;

    /**
     * @var string
     *
     * @ORM\Column(name="BL7", type="string", length=255)
     */
    private $bL7;

    /**
     * @var string
     *
     * @ORM\Column(name="BL8", type="string", length=255)
     */
    private $bL8;

    /**
     * @var string
     *
     * @ORM\Column(name="CR1", type="string", length=255)
     */
    private $cR1;

    /**
     * @var string
     *
     * @ORM\Column(name="CR2", type="string", length=255)
     */
    private $cR2;

    /**
     * @var string
     *
     * @ORM\Column(name="CR3", type="string", length=255)
     */
    private $cR3;

    /**
     * @var string
     *
     * @ORM\Column(name="CR4", type="string", length=255)
     */
    private $cR4;

    /**
     * @var string
     *
     * @ORM\Column(name="CR5", type="string", length=255)
     */
    private $cR5;

    /**
     * @var string
     *
     * @ORM\Column(name="CR6", type="string", length=255)
     */
    private $cR6;

    /**
     * @var string
     *
     * @ORM\Column(name="CR7", type="string", length=255)
     */
    private $cR7;

    /**
     * @var string
     *
     * @ORM\Column(name="CR8", type="string", length=255)
     */
    private $cR8;

    /**
     * @var string
     *
     * @ORM\Column(name="CL1", type="string", length=255)
     */
    private $cL1;

    /**
     * @var string
     *
     * @ORM\Column(name="CL2", type="string", length=255)
     */
    private $cL2;

    /**
     * @var string
     *
     * @ORM\Column(name="CL3", type="string", length=255)
     */
    private $cL3;

    /**
     * @var string
     *
     * @ORM\Column(name="CL4", type="string", length=255)
     */
    private $cL4;

    /**
     * @var string
     *
     * @ORM\Column(name="CL5", type="string", length=255)
     */
    private $cL5;

    /**
     * @var string
     *
     * @ORM\Column(name="CL6", type="string", length=255)
     */
    private $cL6;

    /**
     * @var string
     *
     * @ORM\Column(name="CL7", type="string", length=255)
     */
    private $cL7;

    /**
     * @var string
     *
     * @ORM\Column(name="CL8", type="string", length=255)
     */
    private $cL8;

    /**
     * @var string
     *
     * @ORM\Column(name="DR1", type="string", length=255)
     */
    private $dR1;

    /**
     * @var string
     *
     * @ORM\Column(name="DR2", type="string", length=255)
     */
    private $dR2;

    /**
     * @var string
     *
     * @ORM\Column(name="DR3", type="string", length=255)
     */
    private $dR3;

    /**
     * @var string
     *
     * @ORM\Column(name="DR4", type="string", length=255)
     */
    private $dR4;

    /**
     * @var string
     *
     * @ORM\Column(name="DR5", type="string", length=255)
     */
    private $dR5;

    /**
     * @var string
     *
     * @ORM\Column(name="DR6", type="string", length=255)
     */
    private $dR6;

    /**
     * @var string
     *
     * @ORM\Column(name="DR7", type="string", length=255)
     */
    private $dR7;

    /**
     * @var string
     *
     * @ORM\Column(name="DR8", type="string", length=255)
     */
    private $dR8;

    /**
     * @var string
     *
     * @ORM\Column(name="DL1", type="string", length=255)
     */
    private $dL1;

    /**
     * @var string
     *
     * @ORM\Column(name="DL2", type="string", length=255)
     */
    private $dL2;

    /**
     * @var string
     *
     * @ORM\Column(name="DL3", type="string", length=255)
     */
    private $dL3;

    /**
     * @var string
     *
     * @ORM\Column(name="DL4", type="string", length=255)
     */
    private $dL4;

    /**
     * @var string
     *
     * @ORM\Column(name="DL5", type="string", length=255)
     */
    private $dL5;

    /**
     * @var string
     *
     * @ORM\Column(name="DL6", type="string", length=255)
     */
    private $dL6;

    /**
     * @var string
     *
     * @ORM\Column(name="DL7", type="string", length=255)
     */
    private $dL7;

    /**
     * @var string
     *
     * @ORM\Column(name="DL8", type="string", length=255)
     */
    private $dL8;


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
     * Set optionid
     *
     * @param integer $optionid
     *
     * @return Visit
     */
    public function setOptionid($optionid)
    {
        $this->optionid = $optionid;
    
        return $this;
    }

    /**
     * Get optionid
     *
     * @return integer
     */
    public function getOptionid()
    {
        return $this->optionid;
    }

    /**
     * Set visitName
     *
     * @param string $visitName
     *
     * @return Visit
     */
    public function setVisitName($visitName)
    {
        $this->visitName = $visitName;
    
        return $this;
    }

    /**
     * Get visitName
     *
     * @return string
     */
    public function getVisitName()
    {
        return $this->visitName;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Visit
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
     * Set aR8
     *
     * @param string $aR8
     *
     * @return Visit
     */
    public function setAR8($aR8)
    {
        $this->aR8 = $aR8;
    
        return $this;
    }

    /**
     * Get aR8
     *
     * @return string
     */
    public function getAR8()
    {
        return $this->aR8;
    }

    /**
     * Set aR7
     *
     * @param string $aR7
     *
     * @return Visit
     */
    public function setAR7($aR7)
    {
        $this->aR7 = $aR7;
    
        return $this;
    }

    /**
     * Get aR7
     *
     * @return string
     */
    public function getAR7()
    {
        return $this->aR7;
    }

    /**
     * Set aR6
     *
     * @param string $aR6
     *
     * @return Visit
     */
    public function setAR6($aR6)
    {
        $this->aR6 = $aR6;
    
        return $this;
    }

    /**
     * Get aR6
     *
     * @return string
     */
    public function getAR6()
    {
        return $this->aR6;
    }

    /**
     * Set aR5
     *
     * @param string $aR5
     *
     * @return Visit
     */
    public function setAR5($aR5)
    {
        $this->aR5 = $aR5;
    
        return $this;
    }

    /**
     * Get aR5
     *
     * @return string
     */
    public function getAR5()
    {
        return $this->aR5;
    }

    /**
     * Set aR4
     *
     * @param string $aR4
     *
     * @return Visit
     */
    public function setAR4($aR4)
    {
        $this->aR4 = $aR4;
    
        return $this;
    }

    /**
     * Get aR4
     *
     * @return string
     */
    public function getAR4()
    {
        return $this->aR4;
    }

    /**
     * Set aR3
     *
     * @param string $aR3
     *
     * @return Visit
     */
    public function setAR3($aR3)
    {
        $this->aR3 = $aR3;
    
        return $this;
    }

    /**
     * Get aR3
     *
     * @return string
     */
    public function getAR3()
    {
        return $this->aR3;
    }

    /**
     * Set aR2
     *
     * @param string $aR2
     *
     * @return Visit
     */
    public function setAR2($aR2)
    {
        $this->aR2 = $aR2;
    
        return $this;
    }

    /**
     * Get aR2
     *
     * @return string
     */
    public function getAR2()
    {
        return $this->aR2;
    }

    /**
     * Set aR1
     *
     * @param string $aR1
     *
     * @return Visit
     */
    public function setAR1($aR1)
    {
        $this->aR1 = $aR1;
    
        return $this;
    }

    /**
     * Get aR1
     *
     * @return string
     */
    public function getAR1()
    {
        return $this->aR1;
    }

    /**
     * Set aL1
     *
     * @param string $aL1
     *
     * @return Visit
     */
    public function setAL1($aL1)
    {
        $this->aL1 = $aL1;
    
        return $this;
    }

    /**
     * Get aL1
     *
     * @return string
     */
    public function getAL1()
    {
        return $this->aL1;
    }

    /**
     * Set al2
     *
     * @param string $al2
     *
     * @return Visit
     */
    public function setAl2($al2)
    {
        $this->al2 = $al2;
    
        return $this;
    }

    /**
     * Get al2
     *
     * @return string
     */
    public function getAl2()
    {
        return $this->al2;
    }

    /**
     * Set aL3
     *
     * @param string $aL3
     *
     * @return Visit
     */
    public function setAL3($aL3)
    {
        $this->aL3 = $aL3;
    
        return $this;
    }

    /**
     * Get aL3
     *
     * @return string
     */
    public function getAL3()
    {
        return $this->aL3;
    }

    /**
     * Set aL4
     *
     * @param string $aL4
     *
     * @return Visit
     */
    public function setAL4($aL4)
    {
        $this->aL4 = $aL4;
    
        return $this;
    }

    /**
     * Get aL4
     *
     * @return string
     */
    public function getAL4()
    {
        return $this->aL4;
    }

    /**
     * Set aL5
     *
     * @param string $aL5
     *
     * @return Visit
     */
    public function setAL5($aL5)
    {
        $this->aL5 = $aL5;
    
        return $this;
    }

    /**
     * Get aL5
     *
     * @return string
     */
    public function getAL5()
    {
        return $this->aL5;
    }

    /**
     * Set aL6
     *
     * @param string $aL6
     *
     * @return Visit
     */
    public function setAL6($aL6)
    {
        $this->aL6 = $aL6;
    
        return $this;
    }

    /**
     * Get aL6
     *
     * @return string
     */
    public function getAL6()
    {
        return $this->aL6;
    }

    /**
     * Set aL7
     *
     * @param string $aL7
     *
     * @return Visit
     */
    public function setAL7($aL7)
    {
        $this->aL7 = $aL7;
    
        return $this;
    }

    /**
     * Get aL7
     *
     * @return string
     */
    public function getAL7()
    {
        return $this->aL7;
    }

    /**
     * Set aL8
     *
     * @param string $aL8
     *
     * @return Visit
     */
    public function setAL8($aL8)
    {
        $this->aL8 = $aL8;
    
        return $this;
    }

    /**
     * Get aL8
     *
     * @return string
     */
    public function getAL8()
    {
        return $this->aL8;
    }

    /**
     * Set bR1
     *
     * @param string $bR1
     *
     * @return Visit
     */
    public function setBR1($bR1)
    {
        $this->bR1 = $bR1;
    
        return $this;
    }

    /**
     * Get bR1
     *
     * @return string
     */
    public function getBR1()
    {
        return $this->bR1;
    }

    /**
     * Set bR2
     *
     * @param string $bR2
     *
     * @return Visit
     */
    public function setBR2($bR2)
    {
        $this->bR2 = $bR2;
    
        return $this;
    }

    /**
     * Get bR2
     *
     * @return string
     */
    public function getBR2()
    {
        return $this->bR2;
    }

    /**
     * Set bR3
     *
     * @param string $bR3
     *
     * @return Visit
     */
    public function setBR3($bR3)
    {
        $this->bR3 = $bR3;
    
        return $this;
    }

    /**
     * Get bR�
     *
     * @return string
     */
    public function getBR�()
    {
        return $this->bR�;
    }

    /**
     * Set bR4
     *
     * @param string $bR4
     *
     * @return Visit
     */
    public function setBR4($bR4)
    {
        $this->bR4 = $bR4;
    
        return $this;
    }

    /**
     * Get bR4
     *
     * @return string
     */
    public function getBR4()
    {
        return $this->bR4;
    }

    /**
     * Set bR5
     *
     * @param string $bR5
     *
     * @return Visit
     */
    public function setBR5($bR5)
    {
        $this->bR5 = $bR5;
    
        return $this;
    }

    /**
     * Get bR5
     *
     * @return string
     */
    public function getBR5()
    {
        return $this->bR5;
    }

    /**
     * Set bR6
     *
     * @param string $bR6
     *
     * @return Visit
     */
    public function setBR6($bR6)
    {
        $this->bR6 = $bR6;
    
        return $this;
    }

    /**
     * Get bR6
     *
     * @return string
     */
    public function getBR6()
    {
        return $this->bR6;
    }

    /**
     * Set bR7
     *
     * @param string $bR7
     *
     * @return Visit
     */
    public function setBR7($bR7)
    {
        $this->bR7 = $bR7;
    
        return $this;
    }

    /**
     * Get bR7
     *
     * @return string
     */
    public function getBR7()
    {
        return $this->bR7;
    }

    /**
     * Set bR8
     *
     * @param string $bR8
     *
     * @return Visit
     */
    public function setBR8($bR8)
    {
        $this->bR8 = $bR8;
    
        return $this;
    }

    /**
     * Get bR8
     *
     * @return string
     */
    public function getBR8()
    {
        return $this->bR8;
    }

    /**
     * Set bL1
     *
     * @param string $bL1
     *
     * @return Visit
     */
    public function setBL1($bL1)
    {
        $this->bL1 = $bL1;
    
        return $this;
    }

    /**
     * Get bL1
     *
     * @return string
     */
    public function getBL1()
    {
        return $this->bL1;
    }

    /**
     * Set bL2
     *
     * @param string $bL2
     *
     * @return Visit
     */
    public function setBL2($bL2)
    {
        $this->bL2 = $bL2;
    
        return $this;
    }

    /**
     * Get bL2
     *
     * @return string
     */
    public function getBL2()
    {
        return $this->bL2;
    }

    /**
     * Set bL3
     *
     * @param string $bL3
     *
     * @return Visit
     */
    public function setBL3($bL3)
    {
        $this->bL3 = $bL3;
    
        return $this;
    }

    /**
     * Get bL3
     *
     * @return string
     */
    public function getBL3()
    {
        return $this->bL3;
    }

    /**
     * Set bL4
     *
     * @param string $bL4
     *
     * @return Visit
     */
    public function setBL4($bL4)
    {
        $this->bL4 = $bL4;
    
        return $this;
    }

    /**
     * Get bL4
     *
     * @return string
     */
    public function getBL4()
    {
        return $this->bL4;
    }

    /**
     * Set bL5
     *
     * @param string $bL5
     *
     * @return Visit
     */
    public function setBL5($bL5)
    {
        $this->bL5 = $bL5;
    
        return $this;
    }

    /**
     * Get bL5
     *
     * @return string
     */
    public function getBL5()
    {
        return $this->bL5;
    }

    /**
     * Set bL6
     *
     * @param string $bL6
     *
     * @return Visit
     */
    public function setBL6($bL6)
    {
        $this->bL6 = $bL6;
    
        return $this;
    }

    /**
     * Get bL6
     *
     * @return string
     */
    public function getBL6()
    {
        return $this->bL6;
    }

    /**
     * Set bL7
     *
     * @param string $bL7
     *
     * @return Visit
     */
    public function setBL7($bL7)
    {
        $this->bL7 = $bL7;
    
        return $this;
    }

    /**
     * Get bL7
     *
     * @return string
     */
    public function getBL7()
    {
        return $this->bL7;
    }

    /**
     * Set bL8
     *
     * @param string $bL8
     *
     * @return Visit
     */
    public function setBL8($bL8)
    {
        $this->bL8 = $bL8;
    
        return $this;
    }

    /**
     * Get bL8
     *
     * @return string
     */
    public function getBL8()
    {
        return $this->bL8;
    }

    /**
     * Set cR1
     *
     * @param string $cR1
     *
     * @return Visit
     */
    public function setCR1($cR1)
    {
        $this->cR1 = $cR1;
    
        return $this;
    }

    /**
     * Get cR1
     *
     * @return string
     */
    public function getCR1()
    {
        return $this->cR1;
    }

    /**
     * Set cR2
     *
     * @param string $cR2
     *
     * @return Visit
     */
    public function setCR2($cR2)
    {
        $this->cR2 = $cR2;
    
        return $this;
    }

    /**
     * Get cR2
     *
     * @return string
     */
    public function getCR2()
    {
        return $this->cR2;
    }

    /**
     * Set cR3
     *
     * @param string $cR3
     *
     * @return Visit
     */
    public function setCR3($cR3)
    {
        $this->cR3 = $cR3;
    
        return $this;
    }

    /**
     * Get cR3
     *
     * @return string
     */
    public function getCR3()
    {
        return $this->cR3;
    }

    /**
     * Set cR4
     *
     * @param string $cR4
     *
     * @return Visit
     */
    public function setCR4($cR4)
    {
        $this->cR4 = $cR4;
    
        return $this;
    }

    /**
     * Get cR4
     *
     * @return string
     */
    public function getCR4()
    {
        return $this->cR4;
    }

    /**
     * Set cR5
     *
     * @param string $cR5
     *
     * @return Visit
     */
    public function setCR5($cR5)
    {
        $this->cR5 = $cR5;
    
        return $this;
    }

    /**
     * Get cR5
     *
     * @return string
     */
    public function getCR5()
    {
        return $this->cR5;
    }

    /**
     * Set cR6
     *
     * @param string $cR6
     *
     * @return Visit
     */
    public function setCR6($cR6)
    {
        $this->cR6 = $cR6;
    
        return $this;
    }

    /**
     * Get cR6
     *
     * @return string
     */
    public function getCR6()
    {
        return $this->cR6;
    }

    /**
     * Set cR7
     *
     * @param string $cR7
     *
     * @return Visit
     */
    public function setCR7($cR7)
    {
        $this->cR7 = $cR7;
    
        return $this;
    }

    /**
     * Get cR7
     *
     * @return string
     */
    public function getCR7()
    {
        return $this->cR7;
    }

    /**
     * Set cR8
     *
     * @param string $cR8
     *
     * @return Visit
     */
    public function setCR8($cR8)
    {
        $this->cR8 = $cR8;
    
        return $this;
    }

    /**
     * Get cR8
     *
     * @return string
     */
    public function getCR8()
    {
        return $this->cR8;
    }

    /**
     * Set cL1
     *
     * @param string $cL1
     *
     * @return Visit
     */
    public function setCL1($cL1)
    {
        $this->cL1 = $cL1;
    
        return $this;
    }

    /**
     * Get cL1
     *
     * @return string
     */
    public function getCL1()
    {
        return $this->cL1;
    }

    /**
     * Set cL2
     *
     * @param string $cL2
     *
     * @return Visit
     */
    public function setCL2($cL2)
    {
        $this->cL2 = $cL2;
    
        return $this;
    }

    /**
     * Get cL2
     *
     * @return string
     */
    public function getCL2()
    {
        return $this->cL2;
    }

    /**
     * Set cL3
     *
     * @param string $cL3
     *
     * @return Visit
     */
    public function setCL3($cL3)
    {
        $this->cL3 = $cL3;
    
        return $this;
    }

    /**
     * Get cL3
     *
     * @return string
     */
    public function getCL3()
    {
        return $this->cL3;
    }

    /**
     * Set cL4
     *
     * @param string $cL4
     *
     * @return Visit
     */
    public function setCL4($cL4)
    {
        $this->cL4 = $cL4;
    
        return $this;
    }

    /**
     * Get cL4
     *
     * @return string
     */
    public function getCL4()
    {
        return $this->cL4;
    }

    /**
     * Set cL5
     *
     * @param string $cL5
     *
     * @return Visit
     */
    public function setCL5($cL5)
    {
        $this->cL5 = $cL5;
    
        return $this;
    }

    /**
     * Get cL5
     *
     * @return string
     */
    public function getCL5()
    {
        return $this->cL5;
    }

    /**
     * Set cL6
     *
     * @param string $cL6
     *
     * @return Visit
     */
    public function setCL6($cL6)
    {
        $this->cL6 = $cL6;
    
        return $this;
    }

    /**
     * Get cL6
     *
     * @return string
     */
    public function getCL6()
    {
        return $this->cL6;
    }

    /**
     * Set cL7
     *
     * @param string $cL7
     *
     * @return Visit
     */
    public function setCL7($cL7)
    {
        $this->cL7 = $cL7;
    
        return $this;
    }

    /**
     * Get cL7
     *
     * @return string
     */
    public function getCL7()
    {
        return $this->cL7;
    }

    /**
     * Set cL8
     *
     * @param string $cL8
     *
     * @return Visit
     */
    public function setCL8($cL8)
    {
        $this->cL8 = $cL8;
    
        return $this;
    }

    /**
     * Get cL8
     *
     * @return string
     */
    public function getCL8()
    {
        return $this->cL8;
    }

    /**
     * Set dR1
     *
     * @param string $dR1
     *
     * @return Visit
     */
    public function setDR1($dR1)
    {
        $this->dR1 = $dR1;
    
        return $this;
    }

    /**
     * Get dR1
     *
     * @return string
     */
    public function getDR1()
    {
        return $this->dR1;
    }

    /**
     * Set dR2
     *
     * @param string $dR2
     *
     * @return Visit
     */
    public function setDR2($dR2)
    {
        $this->dR2 = $dR2;
    
        return $this;
    }

    /**
     * Get dR2
     *
     * @return string
     */
    public function getDR2()
    {
        return $this->dR2;
    }

    /**
     * Set dR3
     *
     * @param string $dR3
     *
     * @return Visit
     */
    public function setDR3($dR3)
    {
        $this->dR3 = $dR3;
    
        return $this;
    }

    /**
     * Get dR3
     *
     * @return string
     */
    public function getDR3()
    {
        return $this->dR3;
    }

    /**
     * Set dR4
     *
     * @param string $dR4
     *
     * @return Visit
     */
    public function setDR4($dR4)
    {
        $this->dR4 = $dR4;
    
        return $this;
    }

    /**
     * Get dR4
     *
     * @return string
     */
    public function getDR4()
    {
        return $this->dR4;
    }

    /**
     * Set dR5
     *
     * @param string $dR5
     *
     * @return Visit
     */
    public function setDR5($dR5)
    {
        $this->dR5 = $dR5;
    
        return $this;
    }

    /**
     * Get dR5
     *
     * @return string
     */
    public function getDR5()
    {
        return $this->dR5;
    }

    /**
     * Set dR6
     *
     * @param string $dR6
     *
     * @return Visit
     */
    public function setDR6($dR6)
    {
        $this->dR6 = $dR6;
    
        return $this;
    }

    /**
     * Get dR6
     *
     * @return string
     */
    public function getDR6()
    {
        return $this->dR6;
    }

    /**
     * Set dR7
     *
     * @param string $dR7
     *
     * @return Visit
     */
    public function setDR7($dR7)
    {
        $this->dR7 = $dR7;
    
        return $this;
    }

    /**
     * Get dR7
     *
     * @return string
     */
    public function getDR7()
    {
        return $this->dR7;
    }

    /**
     * Set dR8
     *
     * @param string $dR8
     *
     * @return Visit
     */
    public function setDR8($dR8)
    {
        $this->dR8 = $dR8;
    
        return $this;
    }

    /**
     * Get dR8
     *
     * @return string
     */
    public function getDR8()
    {
        return $this->dR8;
    }

    /**
     * Set dL1
     *
     * @param string $dL1
     *
     * @return Visit
     */
    public function setDL1($dL1)
    {
        $this->dL1 = $dL1;
    
        return $this;
    }

    /**
     * Get dL1
     *
     * @return string
     */
    public function getDL1()
    {
        return $this->dL1;
    }

    /**
     * Set dL2
     *
     * @param string $dL2
     *
     * @return Visit
     */
    public function setDL2($dL2)
    {
        $this->dL2 = $dL2;
    
        return $this;
    }

    /**
     * Get dL2
     *
     * @return string
     */
    public function getDL2()
    {
        return $this->dL2;
    }

    /**
     * Set dL3
     *
     * @param string $dL3
     *
     * @return Visit
     */
    public function setDL3($dL3)
    {
        $this->dL3 = $dL3;
    
        return $this;
    }

    /**
     * Get dL3
     *
     * @return string
     */
    public function getDL3()
    {
        return $this->dL3;
    }

    /**
     * Set dL4
     *
     * @param string $dL4
     *
     * @return Visit
     */
    public function setDL4($dL4)
    {
        $this->dL4 = $dL4;
    
        return $this;
    }

    /**
     * Get dL4
     *
     * @return string
     */
    public function getDL4()
    {
        return $this->dL4;
    }

    /**
     * Set dL5
     *
     * @param string $dL5
     *
     * @return Visit
     */
    public function setDL5($dL5)
    {
        $this->dL5 = $dL5;
    
        return $this;
    }

    /**
     * Get dL5
     *
     * @return string
     */
    public function getDL5()
    {
        return $this->dL5;
    }

    /**
     * Set dL6
     *
     * @param string $dL6
     *
     * @return Visit
     */
    public function setDL6($dL6)
    {
        $this->dL6 = $dL6;
    
        return $this;
    }

    /**
     * Get dL6
     *
     * @return string
     */
    public function getDL6()
    {
        return $this->dL6;
    }

    /**
     * Set dL7
     *
     * @param string $dL7
     *
     * @return Visit
     */
    public function setDL7($dL7)
    {
        $this->dL7 = $dL7;
    
        return $this;
    }

    /**
     * Get dL7
     *
     * @return string
     */
    public function getDL7()
    {
        return $this->dL7;
    }

    /**
     * Set dL8
     *
     * @param string $dL8
     *
     * @return Visit
     */
    public function setDL8($dL8)
    {
        $this->dL8 = $dL8;
    
        return $this;
    }

    /**
     * Get dL8
     *
     * @return string
     */
    public function getDL8()
    {
        return $this->dL8;
    }
}

