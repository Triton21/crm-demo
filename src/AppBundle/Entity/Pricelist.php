<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pricelist
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PricelistRepository")
 */
class Pricelist
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
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="priceName", type="string", length=255)
     */
    private $priceName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="displayPrice", type="string", length=255)
     */
    private $displayPrice;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="active", type="integer")
     */
    private $active;


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
     * Set category
     *
     * @param string $category
     *
     * @return Pricelist
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Pricelist
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set priceName
     *
     * @param string $priceName
     *
     * @return Pricelist
     */
    public function setPriceName($priceName)
    {
        $this->priceName = $priceName;
    
        return $this;
    }

    /**
     * Get priceName
     *
     * @return string
     */
    public function getPriceName()
    {
        return $this->priceName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Pricelist
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set displayPrice
     *
     * @param string $displayPrice
     *
     * @return Pricelist
     */
    public function setDisplayPrice($displayPrice)
    {
        $this->displayPrice = $displayPrice;
    
        return $this;
    }

    /**
     * Get displayPrice
     *
     * @return string
     */
    public function getDisplayPrice()
    {
        return $this->displayPrice;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Pricelist
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set active
     *
     * @param integer $active
     *
     * @return Pricelist
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return integer
     */
    public function getActive()
    {
        return $this->active;
    }
}
