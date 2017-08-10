<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mainfilter
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MainfilterRepository")
 */
class Mainfilter
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
     * @ORM\Column(name="settid", type="integer")
     */
    private $settid;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="filtertext", type="string", length=255)
     */
    private $filtertext;


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
     * @return Mainfilter
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
     * Set type
     *
     * @param integer $type
     *
     * @return Mainfilter
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set filtertext
     *
     * @param string $filtertext
     *
     * @return Mainfilter
     */
    public function setFiltertext($filtertext)
    {
        $this->filtertext = $filtertext;
    
        return $this;
    }

    /**
     * Get filtertext
     *
     * @return string
     */
    public function getFiltertext()
    {
        return $this->filtertext;
    }
}

