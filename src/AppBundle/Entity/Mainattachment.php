<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mainattachment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MainattachmentRepository")
 */
class Mainattachment
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
     * @ORM\ManyToOne(targetEntity="Maininbox", inversedBy="mainattachments")
     * @ORM\JoinColumn(name="maininbox_id", referencedColumnName="id")
     */
    private $maininbox;

    /**
     * @var integer
     *
     * @ORM\Column(name="settid", type="integer")
     */
    private $settid;

    /**
     * @var integer
     *
     * @ORM\Column(name="emailpublicid", type="integer")
     */
    private $emailpublicid;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="attpublicid", type="integer")
     */
    private $attpublicid;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;


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
     * Set settid
     *
     * @param integer $settid
     *
     * @return Mainattachment
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
     * Set emailpublicid
     *
     * @param integer $emailpublicid
     *
     * @return Mainattachment
     */
    public function setEmailpublicid($emailpublicid)
    {
        $this->emailpublicid = $emailpublicid;
    
        return $this;
    }

    /**
     * Get emailpublicid
     *
     * @return integer
     */
    public function getEmailpublicid()
    {
        return $this->emailpublicid;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Mainattachment
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    
        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Mainattachment
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
     * Set path
     *
     * @param string $path
     *
     * @return Mainattachment
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set attpublicid
     *
     * @param integer $attpublicid
     *
     * @return Mainattachment
     */
    public function setAttpublicid($attpublicid)
    {
        $this->attpublicid = $attpublicid;
    
        return $this;
    }

    /**
     * Get attpublicid
     *
     * @return integer
     */
    public function getAttpublicid()
    {
        return $this->attpublicid;
    }

    /**
     * Set maininbox
     *
     * @param \AppBundle\Entity\Maininbox $maininbox
     *
     * @return Mainattachment
     */
    public function setMaininbox(\AppBundle\Entity\Maininbox $maininbox = null)
    {
        $this->maininbox = $maininbox;
    
        return $this;
    }

    /**
     * Get maininbox
     *
     * @return \AppBundle\Entity\Maininbox
     */
    public function getMaininbox()
    {
        return $this->maininbox;
    }
}
