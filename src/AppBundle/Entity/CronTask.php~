<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CronTask
 * 
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity("name")
 */
class CronTask {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="array")
     */
    private $commands;

    /**
     * @ORM\Column(name="`interval`", type="integer")
     */
    private $interval;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastrun;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $arg;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return CronTask
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set commands
     *
     * @param array $commands
     *
     * @return CronTask
     */
    public function setCommands($commands) {
        $this->commands = $commands;

        return $this;
    }

    /**
     * Get commands
     *
     * @return array
     */
    public function getCommands() {
        return $this->commands;
    }

    /**
     * Set interval
     *
     * @param integer $interval
     *
     * @return CronTask
     */
    public function setInterval($interval) {
        $this->interval = $interval;

        return $this;
    }

    /**
     * Get interval
     *
     * @return integer
     */
    public function getInterval() {
        return $this->interval;
    }

    /**
     * Set lastrun
     *
     * @param \DateTime $lastrun
     *
     * @return CronTask
     */
    public function setLastrun($lastrun) {
        $this->lastrun = $lastrun;

        return $this;
    }

    /**
     * Get lastrun
     *
     * @return \DateTime
     */
    public function getLastrun() {
        return $this->lastrun;
    }


    /**
     * Set arg
     *
     * @param string $arg
     *
     * @return CronTask
     */
    public function setArg($arg)
    {
        $this->arg = $arg;
    
        return $this;
    }

    /**
     * Get arg
     *
     * @return string
     */
    public function getArg()
    {
        return $this->arg;
    }
}
