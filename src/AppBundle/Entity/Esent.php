<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Esent
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\EsentRepository")
 */
class Esent {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Elist", inversedBy="esents")
     * @ORM\JoinColumn(name="elist_id", referencedColumnName="id")
     */
    protected $elist;

    /**
     * @var integer
     *
     * @ORM\Column(name="CampId", type="integer")
     */
    private $campId;

    /**
     * @var integer
     *
     * @ORM\Column(name="TaskId", type="integer", nullable=true)
     */
    private $taskId;

    /**
     * @var string
     *
     * @ORM\Column(name="FullName", type="string", length=255, nullable=true)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=255)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="TempId", type="integer")
     */
    private $tempId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set elistId
     *
     * @param integer $elistId
     *
     * @return Esent
     */
    public function setElistId($elistId) {
        $this->elistId = $elistId;

        return $this;
    }

    /**
     * Get elistId
     *
     * @return integer
     */
    public function getElistId() {
        return $this->elistId;
    }

    /**
     * Set campId
     *
     * @param integer $campId
     *
     * @return Esent
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
     * Set fullName
     *
     * @param string $fullName
     *
     * @return Esent
     */
    public function setFullName($fullName) {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName() {
        return $this->fullName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Esent
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set tempId
     *
     * @param integer $tempId
     *
     * @return Esent
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Esent
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
     * Set elist
     *
     * @param \AppBundle\Entity\Elist $elist
     *
     * @return Esent
     */
    public function setElist(\AppBundle\Entity\Elist $elist = null) {
        $this->elist = $elist;

        return $this;
    }

    /**
     * Get elist
     *
     * @return \AppBundle\Entity\Elist
     */
    public function getElist() {
        return $this->elist;
    }


    /**
     * Set taskId
     *
     * @param integer $taskId
     *
     * @return Esent
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;
    
        return $this;
    }

    /**
     * Get taskId
     *
     * @return integer
     */
    public function getTaskId()
    {
        return $this->taskId;
    }
}
