<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmailTask
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\EmailTaskRepository")
 */
class EmailTask {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreatedAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="Nextcons", type="string", length=255)
     */
    private $nextcons;

    /**
     * @var integer
     *
     * @ORM\Column(name="CampId", type="integer")
     */
    private $campId;

    /**
     * @var integer
     *
     * @ORM\Column(name="SettId", type="integer")
     */
    private $settId;

    /**
     * @var string
     *
     * @ORM\Column(name="TempId", type="integer")
     */
    private $tempId;

    /**
     * @var integer
     *
     * @ORM\Column(name="Counter", type="integer", nullable=true)
     */
    private $counter;

    /**
     * @var integer
     *
     * @ORM\Column(name="Leadnum", type="integer")
     */
    private $leadnum;

    /**
     * @var integer
     *
     * @ORM\Column(name="Startid", type="integer")
     */
    private $startid;

    /**
     * @var integer
     *
     * @ORM\Column(name="attid", type="integer" , nullable=true)
     */
    private $attid;

    /**
     * @var integer
     *
     * @ORM\Column(name="Delay", type="integer")
     */
    private $delay;

    /**
     * @var integer
     *
     * @ORM\Column(name="Done", type="integer", nullable=true)
     */
    private $done;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set campId
     *
     * @param integer $campId
     *
     * @return EmailTask
     */
    public function setCampId($campId) {
        $this->campId = $campId;

        return $this;
    }

    /**
     * Get campId
     *
     * @return integer
     */
    public function getCampId() {
        return $this->campId;
    }

    /**
     * Set settId
     *
     * @param integer $settId
     *
     * @return EmailTask
     */
    public function setSettId($settId) {
        $this->settId = $settId;

        return $this;
    }

    /**
     * Get settId
     *
     * @return integer
     */
    public function getSettId() {
        return $this->settId;
    }

    /**
     * Set tempId
     *
     * @param integer $tempId
     *
     * @return EmailTask
     */
    public function setTempId($tempId) {
        $this->tempId = $tempId;

        return $this;
    }

    /**
     * Get tempId
     *
     * @return integer
     */
    public function getTempId() {
        return $this->tempId;
    }

    /**
     * Set counter
     *
     * @param integer $counter
     *
     * @return EmailTask
     */
    public function setCounter($counter) {
        $this->counter = $counter;

        return $this;
    }

    /**
     * Get counter
     *
     * @return integer
     */
    public function getCounter() {
        return $this->counter;
    }

    /**
     * Set done
     *
     * @param integer $done
     *
     * @return EmailTask
     */
    public function setDone($done) {
        $this->done = $done;

        return $this;
    }

    /**
     * Get done
     *
     * @return integer
     */
    public function getDone() {
        return $this->done;
    }

    /**
     * Set lastid
     *
     * @param integer $lastid
     *
     * @return EmailTask
     */
    public function setLastid($lastid) {
        $this->lastid = $lastid;

        return $this;
    }

    /**
     * Get lastid
     *
     * @return integer
     */
    public function getLastid() {
        return $this->lastid;
    }

    /**
     * Set startid
     *
     * @param integer $startid
     *
     * @return EmailTask
     */
    public function setStartid($startid) {
        $this->startid = $startid;

        return $this;
    }

    /**
     * Get startid
     *
     * @return integer
     */
    public function getStartid() {
        return $this->startid;
    }

    /**
     * Set delay
     *
     * @param integer $delay
     *
     * @return EmailTask
     */
    public function setDelay($delay) {
        $this->delay = $delay;

        return $this;
    }

    /**
     * Get delay
     *
     * @return integer
     */
    public function getDelay() {
        return $this->delay;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return EmailTask
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
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
     * Set nextcons
     *
     * @param string $nextcons
     *
     * @return EmailTask
     */
    public function setNextcons($nextcons) {
        $this->nextcons = $nextcons;

        return $this;
    }

    /**
     * Get nextcons
     *
     * @return string
     */
    public function getNextcons() {
        return $this->nextcons;
    }

    /**
     * Set leadnum
     *
     * @param integer $leadnum
     *
     * @return EmailTask
     */
    public function setLeadnum($leadnum) {
        $this->leadnum = $leadnum;

        return $this;
    }

    /**
     * Get leadnum
     *
     * @return integer
     */
    public function getLeadnum() {
        return $this->leadnum;
    }


    /**
     * Set attid
     *
     * @param integer $attid
     *
     * @return EmailTask
     */
    public function setAttid($attid)
    {
        $this->attid = $attid;
    
        return $this;
    }

    /**
     * Get attid
     *
     * @return integer
     */
    public function getAttid()
    {
        return $this->attid;
    }
}
