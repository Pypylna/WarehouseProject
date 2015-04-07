<?php

namespace WarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 * @ORM\Entity(repositoryClass="WarehouseBundle\Repository\ProductRepository")
 * @ORM\Table()
 */
class Product
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
     * @ORM\ManyToOne(targetEntity="MetaProduct")
     */
    private $metaProduct;

    /**
     * @ORM\ManyToOne(targetEntity="Store")
	 * @ORM\JoinColumn(name="store", referencedColumnName="id")
     */
    private $store;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expireAt", type="date")
     */
    private $expireAt;


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
     * Set metaProduct
     *
     * @param string $metaProduct
     * @return Product
     */
    public function setMetaProduct($metaProduct)
    {
        $this->metaProduct = $metaProduct;

        return $this;
    }

    /**
     * Get metaProduct
     *
     * @return string 
     */
    public function getMetaProduct()
    {
        return $this->metaProduct;
    }

    /**
     * Set store
     *
     * @param \stdClass $store
     * @return Product
     */
    public function setStore($store)
    {
        $this->store = $store;

        return $this;
    }

    /**
     * Get store
     *
     * @return \stdClass 
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Product
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set expireAt
     *
     * @param \DateTime $expireAt
     * @return Product
     */
    public function setExpireAt($expireAt)
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    /**
     * Get expireAt
     *
     * @return \DateTime 
     */
    public function getExpireAt()
    {
        return $this->expireAt;
    }
}
