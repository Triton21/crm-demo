<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Texttemplate
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TexttemplateRepository")
 */
class Texttemplate
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
     * @ORM\Column(name="templateName", type="string", length=255)
     */
    private $templateName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="textBody", type="text")
     */
    private $textBody;


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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Texttemplate
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
     * Set username
     *
     * @param string $username
     *
     * @return Texttemplate
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
     * Set textBody
     *
     * @param string $textBody
     *
     * @return Texttemplate
     */
    public function setTextBody($textBody)
    {
        $this->textBody = $textBody;
    
        return $this;
    }

    /**
     * Get textBody
     *
     * @return string
     */
    public function getTextBody()
    {
        return $this->textBody;
    }

    /**
     * Set templateName
     *
     * @param string $templateName
     *
     * @return Texttemplate
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
    
        return $this;
    }

    /**
     * Get templateName
     *
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }
}
