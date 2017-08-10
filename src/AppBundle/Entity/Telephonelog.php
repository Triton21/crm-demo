<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Telephonelog
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TelephonelogRepository")
 */
class Telephonelog {

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
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deadline", type="datetime", nullable=true )
     */
    private $deadline;

    /**
     * @var string
     *
     * @ORM\Column(name="customerName", type="string", length=255)
     */
    private $customerName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="customerEmail", type="string", length=255, nullable=true)
     */
    private $customerEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="leadid", type="string", length=255, nullable=true)
     */
    private $leadid;
    
    /**
     * @var string
     *
     * @ORM\Column(name="solved", type="string", length=255, nullable=true)
     */
    private $solved;

    /**
     * @var string
     *
     * @ORM\Column(name="inorout", type="string", length=255)
     */
    private $inorout;

    /**
     * @var string
     *
     * @ORM\Column(name="customerTel", type="string", length=255, nullable=true)
     */
    private $customerTel;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", length=5000)
     */
    private $note;
    
    /**
     * @var string
     *
     * @ORM\Column(name="remindernote", type="text", length=5000, nullable=true)
     */
    private $remindernote;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reminder", type="datetime", nullable=true )
     */
    private $reminder;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="text", length=5000, nullable=true)
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="assign", type="string", length=255, nullable=true)
     */
    private $assign;

    /**
     * @var string
     *
     * @ORM\Column(name="request", type="string", length=255, nullable=true)
     */
    private $request;

    /**
     * @var string
     *
     * @ORM\Column(name="reassign", type="string", length=255, nullable=true)
     */
    private $reassign;

    /**
     * @var string
     *
     * @ORM\Column(name="flag", type="string", length=255, nullable=true)
     */
    private $flag;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dob", type="datetime", nullable=true)
     */
    private $dob;
    
    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Telephonelog
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
     * Set customerName
     *
     * @param string $customerName
     *
     * @return Telephonelog
     */
    public function setCustomerName($customerName) {
        $this->customerName = $customerName;

        return $this;
    }

    /**
     * Get customerName
     *
     * @return string
     */
    public function getCustomerName() {
        return $this->customerName;
    }

    /**
     * Set inorout
     *
     * @param string $inorout
     *
     * @return Telephonelog
     */
    public function setInorout($inorout) {
        $this->inorout = $inorout;

        return $this;
    }

    /**
     * Get inorout
     *
     * @return string
     */
    public function getInorout() {
        return $this->inorout;
    }

    /**
     * Set customerTel
     *
     * @param string $customerTel
     *
     * @return Telephonelog
     */
    public function setCustomerTel($customerTel) {
        $this->customerTel = $customerTel;

        return $this;
    }

    /**
     * Get customerTel
     *
     * @return string
     */
    public function getCustomerTel() {
        return $this->customerTel;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Telephonelog
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
     * Set action
     *
     * @param string $action
     *
     * @return Telephonelog
     */
    public function setAction($action) {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * Set assign
     *
     * @param string $assign
     *
     * @return Telephonelog
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
     * Set request
     *
     * @param string $request
     *
     * @return Telephonelog
     */
    public function setRequest($request) {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return string
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * Set reassign
     *
     * @param string $reassign
     *
     * @return Telephonelog
     */
    public function setReassign($reassign) {
        $this->reassign = $reassign;

        return $this;
    }

    /**
     * Get reassign
     *
     * @return string
     */
    public function getReassign() {
        return $this->reassign;
    }

    /**
     * Set flag
     *
     * @param string $flag
     *
     * @return Telephonelog
     */
    public function setFlag($flag) {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return string
     */
    public function getFlag() {
        return $this->flag;
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     *
     * @return Telephonelog
     */
    public function setDeadline($deadline) {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime
     */
    public function getDeadline() {
        return $this->deadline;
    }


    /**
     * Set leadid
     *
     * @param string $leadid
     *
     * @return Telephonelog
     */
    public function setLeadid($leadid)
    {
        $this->leadid = $leadid;
    
        return $this;
    }

    /**
     * Get leadid
     *
     * @return string
     */
    public function getLeadid()
    {
        return $this->leadid;
    }

    /**
     * Set customerEmail
     *
     * @param string $customerEmail
     *
     * @return Telephonelog
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;
    
        return $this;
    }

    /**
     * Get customerEmail
     *
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * Set solved
     *
     * @param string $solved
     *
     * @return Telephonelog
     */
    public function setSolved($solved)
    {
        $this->solved = $solved;
    
        return $this;
    }

    /**
     * Get solved
     *
     * @return string
     */
    public function getSolved()
    {
        return $this->solved;
    }

    /**
     * Set remindernote
     *
     * @param string $remindernote
     *
     * @return Telephonelog
     */
    public function setRemindernote($remindernote)
    {
        $this->remindernote = $remindernote;
    
        return $this;
    }

    /**
     * Get remindernote
     *
     * @return string
     */
    public function getRemindernote()
    {
        return $this->remindernote;
    }

    /**
     * Set reminder
     *
     * @param \DateTime $reminder
     *
     * @return Telephonelog
     */
    public function setReminder($reminder)
    {
        $this->reminder = $reminder;
    
        return $this;
    }

    /**
     * Get reminder
     *
     * @return \DateTime
     */
    public function getReminder()
    {
        return $this->reminder;
    }

    /**
     * Set dob
     *
     * @param \DateTime $dob
     *
     * @return Telephonelog
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
    
        return $this;
    }

    /**
     * Get dob
     *
     * @return \DateTime
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Telephonelog
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return Telephonelog
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    
        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }
}
