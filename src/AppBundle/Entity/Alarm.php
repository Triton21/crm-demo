<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alarm
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AlarmRepository")
 */
class Alarm
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
     * @var integer
     *
     * @ORM\Column(name="leadid", type="integer", nullable=true)
     */
    private $leadid;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="logid", type="integer", nullable=true)
     */
    private $logid;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=255)
     */
    private $user;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cName", type="string", length=255)
     */
    private $cName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="alarmAt", type="datetime")
     */
    private $alarmAt;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=255, nullable=true)
     */
    private $note;


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
     * Set leadid
     *
     * @param integer $leadid
     *
     * @return Alarm
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
     * Set user
     *
     * @param string $user
     *
     * @return Alarm
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Alarm
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
     * Set alarmAt
     *
     * @param \DateTime $alarmAt
     *
     * @return Alarm
     */
    public function setAlarmAt($alarmAt)
    {
        $this->alarmAt = $alarmAt;
    
        return $this;
    }

    /**
     * Get alarmAt
     *
     * @return \DateTime
     */
    public function getAlarmAt()
    {
        return $this->alarmAt;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Alarm
     */
    public function setNote($note)
    {
        $this->note = $note;
    
        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set cName
     *
     * @param string $cName
     *
     * @return Alarm
     */
    public function setCName($cName)
    {
        $this->cName = $cName;
    
        return $this;
    }

    /**
     * Get cName
     *
     * @return string
     */
    public function getCName()
    {
        return $this->cName;
    }

    /**
     * Set logid
     *
     * @param integer $logid
     *
     * @return Alarm
     */
    public function setLogid($logid)
    {
        $this->logid = $logid;
    
        return $this;
    }

    /**
     * Get logid
     *
     * @return integer
     */
    public function getLogid()
    {
        return $this->logid;
    }
}
