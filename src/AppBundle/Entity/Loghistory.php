<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Loghistory
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\LoghistoryRepository")
 */
class Loghistory
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
     * @var \DateTime
     *
     * @ORM\Column(name="calldate", type="datetime")
     */
    private $calldate;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", length=5000)
     */
    private $note;

    /**
     * @var string
     *
     * @ORM\Column(name="assign", type="string", length=255)
     */
    private $assign;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="Logid", type="string", length=255)
     */
    private $logid;


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
     * Set calldate
     *
     * @param \DateTime $calldate
     *
     * @return Loghistory
     */
    public function setCalldate($calldate)
    {
        $this->calldate = $calldate;
    
        return $this;
    }

    /**
     * Get calldate
     *
     * @return \DateTime
     */
    public function getCalldate()
    {
        return $this->calldate;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Loghistory
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
     * Set assign
     *
     * @param string $assign
     *
     * @return Loghistory
     */
    public function setAssign($assign)
    {
        $this->assign = $assign;
    
        return $this;
    }

    /**
     * Get assign
     *
     * @return string
     */
    public function getAssign()
    {
        return $this->assign;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Loghistory
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
     * Set logid
     *
     * @param string $logid
     *
     * @return Loghistory
     */
    public function setLogid($logid)
    {
        $this->logid = $logid;
    
        return $this;
    }

    /**
     * Get logid
     *
     * @return string
     */
    public function getLogid()
    {
        return $this->logid;
    }
}
