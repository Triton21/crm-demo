<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Maininbox
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MainsentRepository")
 */
class Mainsent
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
     * @ORM\Column(name="settid", type="integer", nullable=true)
     */
    private $settid;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="seen", type="integer", nullable=true)
     */
    private $seen;
    
    /**
     * @var string
     *
     * @ORM\Column(name="fromemail", type="string", length=255, nullable=true)
     */
    private $fromemail;
    
    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    private $username;
    
    /**
     * @var string
     *
     * @ORM\Column(name="fromname", type="string", length=255, nullable=true)
     */
    private $fromname;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;
    
    /**
     * @var string
     *
     * @ORM\Column(name="folder", type="string", length=255, nullable=true)
     */
    private $folder;

    /**
     * @var string
     *
     * @ORM\Column(name="mailid", type="string", length=255, nullable=true)
     */
    private $mailid;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;
    
    /**
     * @var string
     *
     * @ORM\Column(name="texthtml", type="text", nullable=true)
     */
    private $texthtml;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    private $source;
    
    /**
     * @var string
     *
     * @ORM\Column(name="toemail", type="string", length=255, nullable=true)
     */
    private $toemail;
    
    /**
     * @var string
     *
     * @ORM\Column(name="toarray", type="array", nullable=true)
     */
    private $toarray;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ccarray", type="array", nullable=true)
     */
    private $ccarray;
    
    /**
     * @var string
     *
     * @ORM\Column(name="toname", type="string", length=255, nullable=true)
     */
    private $toname;
    
    /**
     * @var string
     *
     * @ORM\Column(name="replyto", type="string", length=255, nullable=true)
     */
    private $replyto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="maildate", type="datetime", nullable=true)
     */
    private $maildate;

    /**
     * @var string
     *
     * @ORM\Column(name="attachment", type="string", length=255, nullable=true)
     */
    private $attachment;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="replaidid", type="integer", nullable=true)
     */
    private $replaidid;


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
     * @return Maininbox
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
     * Set fromemail
     *
     * @param string $fromemail
     *
     * @return Maininbox
     */
    public function setFromemail($fromemail)
    {
        $this->fromemail = $fromemail;
    
        return $this;
    }

    /**
     * Get fromemail
     *
     * @return string
     */
    public function getFromemail()
    {
        return $this->fromemail;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Maininbox
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set mailid
     *
     * @param string $mailid
     *
     * @return Maininbox
     */
    public function setMailid($mailid)
    {
        $this->mailid = $mailid;
    
        return $this;
    }

    /**
     * Get mailid
     *
     * @return string
     */
    public function getMailid()
    {
        return $this->mailid;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Maininbox
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Maininbox
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
     * Set source
     *
     * @param string $source
     *
     * @return Maininbox
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
     * Set attachment
     *
     * @param string $attachment
     *
     * @return Maininbox
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
    
        return $this;
    }

    /**
     * Get attachment
     *
     * @return string
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Set folder
     *
     * @param string $folder
     *
     * @return Maininbox
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
    
        return $this;
    }

    /**
     * Get folder
     *
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Set texthtml
     *
     * @param string $texthtml
     *
     * @return Maininbox
     */
    public function setTexthtml($texthtml)
    {
        $this->texthtml = $texthtml;
    
        return $this;
    }

    /**
     * Get texthtml
     *
     * @return string
     */
    public function getTexthtml()
    {
        return $this->texthtml;
    }

    /**
     * Set fromname
     *
     * @param string $fromname
     *
     * @return Maininbox
     */
    public function setFromname($fromname)
    {
        $this->fromname = $fromname;
    
        return $this;
    }

    /**
     * Get fromname
     *
     * @return string
     */
    public function getFromname()
    {
        return $this->fromname;
    }

    /**
     * Set toemail
     *
     * @param string $toemail
     *
     * @return Maininbox
     */
    public function setToemail($toemail)
    {
        $this->toemail = $toemail;
    
        return $this;
    }

    /**
     * Get toemail
     *
     * @return string
     */
    public function getToemail()
    {
        return $this->toemail;
    }

    /**
     * Set replyto
     *
     * @param string $replyto
     *
     * @return Maininbox
     */
    public function setReplyto($replyto)
    {
        $this->replyto = $replyto;
    
        return $this;
    }

    /**
     * Get replyto
     *
     * @return string
     */
    public function getReplyto()
    {
        return $this->replyto;
    }

    /**
     * Set seen
     *
     * @param integer $seen
     *
     * @return Maininbox
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;
    
        return $this;
    }

    /**
     * Get seen
     *
     * @return integer
     */
    public function getSeen()
    {
        return $this->seen;
    }
    

    /**
     * Set toname
     *
     * @param string $toname
     *
     * @return Mainsent
     */
    public function setToname($toname)
    {
        $this->toname = $toname;
    
        return $this;
    }

    /**
     * Get toname
     *
     * @return string
     */
    public function getToname()
    {
        return $this->toname;
    }
    

    

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Mainsent
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set replaidid
     *
     * @param integer $replaidid
     *
     * @return Mainsent
     */
    public function setReplaidid($replaidid)
    {
        $this->replaidid = $replaidid;
    
        return $this;
    }

    /**
     * Get replaidid
     *
     * @return integer
     */
    public function getReplaidid()
    {
        return $this->replaidid;
    }

    /**
     * Set toarray
     *
     * @param array $toarray
     *
     * @return Mainsent
     */
    public function setToarray($toarray)
    {
        $this->toarray = $toarray;
    
        return $this;
    }

    /**
     * Get toarray
     *
     * @return array
     */
    public function getToarray()
    {
        return $this->toarray;
    }

    /**
     * Set ccarray
     *
     * @param array $ccarray
     *
     * @return Mainsent
     */
    public function setCcarray($ccarray)
    {
        $this->ccarray = $ccarray;
    
        return $this;
    }

    /**
     * Get ccarray
     *
     * @return array
     */
    public function getCcarray()
    {
        return $this->ccarray;
    }

    /**
     * Set maildate
     *
     * @param \DateTime $maildate
     *
     * @return Mainsent
     */
    public function setMaildate($maildate)
    {
        $this->maildate = $maildate;
    
        return $this;
    }

    /**
     * Get maildate
     *
     * @return \DateTime
     */
    public function getMaildate()
    {
        return $this->maildate;
    }
}
