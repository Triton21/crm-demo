<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Settings
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SettingsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Settings {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Maininbox", mappedBy="settings")
     */
    private $maininboxes;
    
    public function __construct()
    {
        $this->maininboxes = new ArrayCollection();
    }
    
    /**
     * @var string
     *
     * @ORM\Column(name="smtp", type="string", length=255)
     */
    private $smtp;

    /**
     * @var string
     *
     * @ORM\Column(name="port", type="string", length=255)
     */
    private $port;
    
    /**
     * @var string
     *
     * @ORM\Column(name="incomingSSL", type="string", length=255, nullable=true)
     */
    private $incomingSSL;
    
    /**
     * @var string
     *
     * @ORM\Column(name="imapserver", type="string", length=255, nullable=true)
     */
    private $imapserver;
    
    /**
     * @var string
     *
     * @ORM\Column(name="imapport", type="string", length=255, nullable=true)
     */
    private $imapport;

    /**
     * @var string
     *
     * @ORM\Column(name="auth", type="string", length=255, nullable=true)
     */
    private $auth;
    
    /**
     * @var string
     *
     * @ORM\Column(name="lastemail", type="integer", nullable=true)
     */
    private $lastemail;

    /**
     * @var string
     *
     * @ORM\Column(name="essl", type="string", length=255)
     */
    private $essl;

    /**
     * @var string
     *
     * @ORM\Column(name="eusername", type="text", length=255)
     */
    private $eusername;

    /**
     * @var string
     *
     * @ORM\Column(name="fromname", type="string", length=255, nullable=true)
     */
    private $fromname;

    /**
     * @var string
     *
     * @ORM\Column(name="epassword", type="text", length=255)
     */
    private $epassword;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime")
     */
    private $createdat;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastdownload", type="datetime", nullable=true)
     */
    private $lastdownload;

    /**
     * @var string
     *
     * @ORM\Column(name="settname", type="string", length=255)
     */
    private $settname;
    
    /**
     * @var string
     *
     * @ORM\Column(name="dirname", type="string", length=255, nullable=true)
     */
    private $dirname;

    /**
     * @var string
     *
     * @ORM\Column(name="active", type="integer", nullable=true)
     */
    private $active;
    
    /**
     * @var string
     *
     * @ORM\Column(name="incoming", type="integer", nullable=true)
     */
    private $incoming;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set smtp
     *
     * @param string $smtp
     *
     * @return Settings
     */
    public function setSmtp($smtp) {
        $this->smtp = $smtp;

        return $this;
    }

    /**
     * Get smtp
     *
     * @return string
     */
    public function getSmtp() {
        return $this->smtp;
    }

    /**
     * Set port
     *
     * @param string $port
     *
     * @return Settings
     */
    public function setPort($port) {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return string
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * Set essl
     *
     * @param string $essl
     *
     * @return Settings
     */
    public function setEssl($essl) {
        $this->essl = $essl;

        return $this;
    }

    /**
     * Get essl
     *
     * @return string
     */
    public function getEssl() {
        return $this->essl;
    }

    /**
     * Set eusername
     *
     * @param string $eusername
     *
     * @return Settings
     */
    public function setEusername($eusername) {
        $this->eusername = $eusername;

        return $this;
    }

    /**
     * Get eusername
     *
     * @return string
     */
    public function getEusername() {
        return $this->eusername;
    }

    /**
     * Set epassword
     *
     * @param string $epassword
     *
     * @return Settings
     */
    public function setEpassword($epassword) {
        $this->epassword = $epassword;

        return $this;
    }

    /**
     * Get epassword
     *
     * @return string
     */
    public function getEpassword() {
        return $this->epassword;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Settings
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get settname
     *
     * @return string
     */
    public function getSettname() {
        return $this->settname;
    }

    /**
     * Set settname
     *
     * @param string $settname
     *
     * @return Settings
     */
    public function setSettname($settname) {
        $this->settname = $settname;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Get CreatedAt
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdat;
    }

    /**
     * Set CreatedAt 
     * 
     * @ORM\PreUpdate 
     */
    public function setCreatedAt() {
        $this->createdat = new \DateTime();
    }

    /**
     * Get active
     *
     * @return integer
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * Set active
     *
     * @param integer $active
     *
     * @return Settings
     */
    public function setActive($active) {
        $this->active = $active;

        return $this;
    }

    /**
     * Set fromname
     *
     * @param string $fromname
     *
     * @return Settings
     */
    public function setFromname($fromname) {
        $this->fromname = $fromname;

        return $this;
    }

    /**
     * Get fromname
     *
     * @return string
     */
    public function getFromname() {
        return $this->fromname;
    }


    /**
     * Set auth
     *
     * @param string $auth
     *
     * @return Settings
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
    
        return $this;
    }

    /**
     * Get auth
     *
     * @return string
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Set incoming
     *
     * @param integer $incoming
     *
     * @return Settings
     */
    public function setIncoming($incoming)
    {
        $this->incoming = $incoming;
    
        return $this;
    }

    /**
     * Get incoming
     *
     * @return integer
     */
    public function getIncoming()
    {
        return $this->incoming;
    }

    /**
     * Add maininbox
     *
     * @param \AppBundle\Entity\Maininbox $maininbox
     *
     * @return Settings
     */
    public function addMaininbox(\AppBundle\Entity\Maininbox $maininbox)
    {
        $this->maininboxes[] = $maininbox;
    
        return $this;
    }

    /**
     * Remove maininbox
     *
     * @param \AppBundle\Entity\Maininbox $maininbox
     */
    public function removeMaininbox(\AppBundle\Entity\Maininbox $maininbox)
    {
        $this->maininboxes->removeElement($maininbox);
    }

    /**
     * Get maininboxes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMaininboxes()
    {
        return $this->maininboxes;
    }

    /**
     * Set lastdownload
     *
     * @param \DateTime $lastdownload
     *
     * @return Settings
     */
    public function setLastdownload($lastdownload)
    {
        $this->lastdownload = $lastdownload;
    
        return $this;
    }

    /**
     * Get lastdownload
     *
     * @return \DateTime
     */
    public function getLastdownload()
    {
        return $this->lastdownload;
    }

    /**
     * Set dirname
     *
     * @param string $dirname
     *
     * @return Settings
     */
    public function setDirname($dirname)
    {
        $this->dirname = $dirname;
    
        return $this;
    }

    /**
     * Get dirname
     *
     * @return string
     */
    public function getDirname()
    {
        return $this->dirname;
    }

    /**
     * Set incomingSSL
     *
     * @param string $incomingSSL
     *
     * @return Settings
     */
    public function setIncomingSSL($incomingSSL)
    {
        $this->incomingSSL = $incomingSSL;
    
        return $this;
    }

    /**
     * Get incomingSSL
     *
     * @return string
     */
    public function getIncomingSSL()
    {
        return $this->incomingSSL;
    }

    /**
     * Set imapserver
     *
     * @param string $imapserver
     *
     * @return Settings
     */
    public function setImapserver($imapserver)
    {
        $this->imapserver = $imapserver;
    
        return $this;
    }

    /**
     * Get imapserver
     *
     * @return string
     */
    public function getImapserver()
    {
        return $this->imapserver;
    }

    /**
     * Set imapport
     *
     * @param string $imapport
     *
     * @return Settings
     */
    public function setImapport($imapport)
    {
        $this->imapport = $imapport;
    
        return $this;
    }

    /**
     * Get imapport
     *
     * @return string
     */
    public function getImapport()
    {
        return $this->imapport;
    }

    /**
     * Set lastemail
     *
     * @param string $lastemail
     *
     * @return Settings
     */
    public function setLastemail($lastemail)
    {
        $this->lastemail = $lastemail;
    
        return $this;
    }

    /**
     * Get lastemail
     *
     * @return string
     */
    public function getLastemail()
    {
        return $this->lastemail;
    }
}
