<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Callhistory
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CallhistoryRepository")
 */
class Callhistory {

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
     * @ORM\Column(name="calldate", type="datetime", length=255)
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
     * @ORM\Column(name="active", type="string", length=255, nullable=true)
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="leadid", type="string", length=255)
     */
    private $leadid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="consdate", type="datetime", nullable=true)
     */
    private $consdate;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set calldate
     *
     * @param \DateTime $calldate
     *
     * @return Callhistory
     */
    public function setCalldate() {
        $this->calldate = new \DateTime();
        return $this;
    }

    /**
     * Get calldate
     *
     * @return \DateTime
     */
    public function getCalldate() {
        return $this->calldate;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Callhistory
     */
    public function setNote($note) {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote() {
        return $this->note;
    }

    /**
     * Set assign
     *
     * @param string $assign
     *
     * @return Callhistory
     */
    public function setAssign($assign) {
        $this->assign = $assign;

        return $this;
    }

    /**
     * Get assign
     *
     * @return string
     */
    public function getAssign() {
        return $this->assign;
    }

    /**
     * Set lead
     *
     * @param \AppBundle\Entity\Lead $lead
     *
     * @return Callhistory
     */
    public function setLead(\AppBundle\Entity\Lead $lead = null) {
        $this->lead = $lead;

        return $this;
    }

    /**
     * Get lead
     *
     * @return \AppBundle\Entity\Lead
     */
    public function getLead() {
        return $this->lead;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Callhistory
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set active
     *
     * @param string $active
     *
     * @return Callhistory
     */
    public function setActive($active) {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return string
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * Set leadid
     *
     * @param string $leadid
     *
     * @return Callhistory
     */
    public function setLeadid($leadid) {
        $this->leadid = $leadid;

        return $this;
    }

    /**
     * Get leadid
     *
     * @return string
     */
    public function getLeadid() {
        return $this->leadid;
    }

    /**
     * Set consdate
     *
     * @param \DateTime $consdate
     *
     * @return Lead
     */
    public function setConsdate($consdate) {
        $this->consdate = $consdate;

        return $this;
    }

    /**
     * Get consdate
     *
     * @return \DateTime
     */
    public function getConsdate() {
        return $this->consdate;
    }

}
