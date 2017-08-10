<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Doctorlist
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Doctorlist
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
     * @ORM\Column(name="dname", type="string", length=255)
     */
    private $dname;

    /**
     * @var integer
     *
     * @ORM\Column(name="active", type="integer")
     */
    private $active;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="feedbackid", type="integer", nullable=true)
     */
    private $feedbackid;

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
     * Set dname
     *
     * @param string $dname
     *
     * @return Doctorlist
     */
    public function setDname($dname)
    {
        $this->dname = $dname;
    
        return $this;
    }

    /**
     * Get dname
     *
     * @return string
     */
    public function getDname()
    {
        return $this->dname;
    }

    /**
     * Set active
     *
     * @param integer $active
     *
     * @return Doctorlist
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return integer
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set feedbackid
     *
     * @param integer $feedbackid
     *
     * @return Doctorlist
     */
    public function setFeedbackid($feedbackid)
    {
        $this->feedbackid = $feedbackid;
    
        return $this;
    }

    /**
     * Get feedbackid
     *
     * @return integer
     */
    public function getFeedbackid()
    {
        return $this->feedbackid;
    }
}
