<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class Swift {

    
    private $id;

    
    private $username;

    
    private $subject;

    
    private $body;

    
    private $smtp;

    
    private $port;

    
    private $mssl;

    
    private $eusername;

    
    private $epassword;

    
    private $emailto;
    
    private $path;
    
    private $attachmentName;

    public function getAttachmentName() {
        return $this->attachmentName;
    }

    
    public function setAttachmentName($attachmentName) {
        $this->attachmentName = $attachmentName;

        return $this;
    }
    
    public function getPath() {
        return $this->path;
    }

    
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }
    
    public function getEmailto() {
        return $this->emailto;
    }

    
    public function setEmailto($emailto) {
        $this->emailto = $emailto;

        return $this;
    }

    
    public function getId() {
        return $this->id;
    }

   
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    
    public function getUsername() {
        return $this->username;
    }

    
    public function setSubject($subject) {
        $this->subject = $subject;

        return $this;
    }

    
    public function getSubject() {
        return $this->subject;
    }

    
    public function setBody($body) {
        $this->body = $body;

        return $this;
    }

    
    public function getBody() {
        return $this->body;
    }

    
    public function setSmtp($smtp) {
        $this->smtp = $smtp;

        return $this;
    }

    
    public function getSmtp() {
        return $this->smtp;
    }

    public function setPort($port) {
        $this->port = $port;

        return $this;
    }

    public function getPort() {
        return $this->port;
    }

    public function setMssl($mssl) {
        $this->mssl = $mssl;

        return $this;
    }

    
    public function getMssl() {
        return $this->mssl;
    }

    public function setEusername($eusername) {
        $this->eusername = $eusername;

        return $this;
    }

    public function getEusername() {
        return $this->eusername;
    }

    public function setEpassword($epassword) {
        $this->epassword = $epassword;

        return $this;
    }

    public function getEpassword() {
        return $this->epassword;
    }

    public function post() {
        
        $transport = \Swift_SmtpTransport::newInstance($this->getSmtp(), $this->getPort(), $this->getMssl())
                ->setUsername($this->getEusername())
                ->setPassword($this->getEpassword())
        ;


        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance($this->getSubject())
                ->setFrom(array($this->getEusername() => $this->getUsername()))
                ->setTo(array($this->getEmailto() => 'Client'))
                ->setBody($this->getBody())
                //->attach($this->getPath())
        ;

        $result = $mailer->send($message);
    }

}
