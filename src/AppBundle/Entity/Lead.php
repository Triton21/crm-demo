<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Lead
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\LeadRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Lead {
    
      
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
     * @ORM\Column(name="customerName", type="string", length=255)
     */
    private $customerName;

    /**
     * @var string
     *
     * @ORM\Column(name="customerEmail", type="text", length=255, nullable=true)
     */
    private $customerEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="customerTel", type="string", length=255, nullable=true)
     */
    private $customerTel;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;
    
        /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    private $source;


    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="called1", type="datetime", nullable=true)
     */
    private $called1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="called2", type="datetime", nullable=true)
     */
    private $called2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="called3", type="datetime", nullable=true)
     */
    private $called3;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="called4", type="datetime", nullable=true)
     */
    private $called4;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="called5", type="datetime", nullable=true)
     */
    private $called5;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="wondate", type="datetime", nullable=true)
     */
    private $wondate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="consdate", type="datetime", nullable=true)
     */
    private $consdate;

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
     * @var \DateTime
     *
     * @ORM\Column(name="dob", type="datetime", nullable=true)
     */
    private $dob;

    /**
     * @var string
     *
     * @ORM\Column(name="remindernote", type="text", length=5000, nullable=true)
     */
    private $remindernote;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reminder", type="datetime", nullable=true)
     */
    private $reminder;
    
        /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastcontact", type="datetime", nullable=true)
     */
    private $lastcontact;
    
    /**
     * @var string
     *
     * @ORM\Column(name="contacted", type="integer", length=255, nullable=true)
     */
    private $contacted;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="probability", type="integer", nullable=true)
     */
    private $probability;
    
        /**
     * @var string
     *
     * @ORM\Column(name="note5", type="string", length=255, nullable=true)
     */
    private $note5;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="assign", type="string", length=255, nullable=true)
     */
    private $assign;

    /**
     * @var string
     *
     * @ORM\Column(name="flag", type="string", length=255, nullable=true)
     */
    private $flag;

    /**
     * @var string
     *
     * @ORM\Column(name="emailid", type="string", length=255, nullable=true)
     */
    private $emailid;
    
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set customerName
     *
     * @param string $customerName
     *
     * @return Lead
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
     * Set customerEmail
     *
     * @param string $customerEmail
     *
     * @return Lead
     */
    public function setCustomerEmail($customerEmail) {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    /**
     * Get customerEmail
     *
     * @return string
     */
    public function getCustomerEmail() {
        return $this->customerEmail;
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
     * Set status
     *
     * @param string $status
     *
     * @return Lead
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
     * Set assign
     *
     * @param string $assign
     *
     * @return Lead
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
     * Set flag
     *
     * @param string $flag
     *
     * @return Lead
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
     * Set emailid
     *
     * @param string $emailid
     *
     * @return Lead
     */
    public function setEmailid($emailid) {
        $this->emailid = $emailid;

        return $this;
    }

    /**
     * Get emailid
     *
     * @return string
     */
    public function getEmailid() {
        return $this->emailid;
    }

    /**
     * Set customerTel
     *
     * @param string $customerTel
     *
     * @return Lead
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
     * Set message
     *
     * @param string $message
     *
     * @return Lead
     */
    public function setMessage($message) {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Get called1
     *
     * @return \DateTime
     */
    public function getCalled1() {
        return $this->called1;
    }

    /**
     * Get called2
     *
     * @return \DateTime
     */
    public function getCalled2() {
        return $this->called2;
    }

    /**
     * Get called3
     *
     * @return \DateTime
     */
    public function getCalled3() {
        return $this->called3;
    }

    /**
     * Get called4
     *
     * @return \DateTime
     */
    public function getCalled4() {
        return $this->called4;
    }

    /**
     * Get called5
     *
     * @return \DateTime
     */
    public function getCalled5() {
        return $this->called5;
    }

    public function __construct() {
        
        $this->callhistory = new ArrayCollection();
    }

    /**
     * Set called1
     *
     * @param \DateTime $called1
     *
     * @return Lead
     * 
     */
    public function setCalled1() {
        $this->called1 = new \DateTime();

        return $this;
    }

    /**
     * Set called2
     *
     * @param \DateTime $called2
     *
     * @return Lead
     * 
     */
    public function setCalled2() {
        $this->called2 = new \DateTime();

        return $this;
    }

    /**
     * Set called3
     *
     * @param \DateTime $called3
     *
     * @return Lead
     * 
     */
    public function setCalled3() {
        $this->called3 = new \DateTime();

        return $this;
    }

    /**
     * Set called4
     *
     * @param \DateTime $called4
     *
     * @return Lead
     * 
     */
    public function setCalled4() {
        $this->called4 = new \DateTime();

        return $this;
    }

    /**
     * Set called5
     *
     * @param \DateTime $called5
     *
     * @return Lead
     * 
     */
    public function setCalled5() {
        $this->called5 = new \DateTime();

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Lead
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set called5pc
     *
     * @param string $called5pc
     *
     * @return Lead
     */
    public function setCalled5pc($called5pc) {
        $this->called5pc = $called5pc;

        return $this;
    }

    /**
     * Get called5pc
     *
     * @return string
     */
    public function getCalled5pc() {
        return $this->called5pc;
    }

    /**
     * Set note5
     *
     * @param string $note5
     *
     * @return Lead
     */
    public function setNote5($note5)
    {
        $this->note5 = $note5;
    
        return $this;
    }

    /**
     * Get note5
     *
     * @return string
     */
    public function getNote5()
    {
        return $this->note5;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return Lead
     */
    public function setSource($source)
    {
        $this->source = $source;
    
        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set wondate
     *
     * @param \DateTime $wondate
     *
     * @return Lead
     */
    public function setWondate()
    {
        $this->wondate = new \DateTime();
    
        return $this;
    }

    /**
     * Get wondate
     *
     * @return \DateTime
     */
    public function getWondate()
    {
        return $this->wondate;
    }
    
 

    /**
     * Set consdate
     *
     * @param \DateTime $consdate
     *
     * @return Lead
     */
    public function setConsdate($consdate)
    {
        $this->consdate = $consdate;
    
        return $this;
    }

    /**
     * Get consdate
     *
     * @return \DateTime
     */
    public function getConsdate()
    {
        return $this->consdate;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Lead
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
     * @return Lead
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

    /**
     * Set dob
     *
     * @param \DateTime $dob
     *
     * @return Lead
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
     * Set remindernote
     *
     * @param string $remindernote
     *
     * @return Lead
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
     * @return Lead
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
     * Set lastcontact
     *
     * @param \DateTime $lastcontact
     *
     * @return Lead
     */
    public function setLastcontact($lastcontact)
    {
        $this->lastcontact = $lastcontact;
    
        return $this;
    }

    /**
     * Get lastcontact
     *
     * @return \DateTime
     */
    public function getLastcontact()
    {
        return $this->lastcontact;
    }

    /**
     * Set contacted
     *
     * @param string $contacted
     *
     * @return Lead
     */
    public function setContacted($contacted)
    {
        $this->contacted = $contacted;
    
        return $this;
    }

    /**
     * Get contacted
     *
     * @return string
     */
    public function getContacted()
    {
        return $this->contacted;
    }

    /**
     * Set probability
     *
     * @param integer $probability
     *
     * @return Lead
     */
    public function setProbability($probability)
    {
        $this->probability = $probability;
    
        return $this;
    }

    /**
     * Get probability
     *
     * @return integer
     */
    public function getProbability()
    {
        return $this->probability;
    }
}
