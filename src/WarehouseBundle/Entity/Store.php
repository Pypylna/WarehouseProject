<?php

namespace WarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Store
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Store
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
     * @ORM\Column(name="name", type="string", length=128)
     * @Assert\Length(
     *     min=3,
     *     minMessage="Nazwa sklepu jest zbyt krótka",
     *     max=128,
     *     maxMessage="Nazwa sklepu nie może być dłuższa niż 128 liter"
     *     )
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="localization", type="string", length=500)
     * @Assert\Length(
     *     min=7,
     *     minMessage="Adres jest zbyt krótki",
     *     max=500,
     *     maxMessage="Adres nie może być dłuższy niż 500 liter"
     *     )
     */
    private $localization;


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
     * Set name
     *
     * @param string $name
     * @return Store
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set localization
     *
     * @param string $localization
     * @return Store
     */
    public function setLocalization($localization)
    {
        $this->localization = $localization;

        return $this;
    }

    /**
     * Get localization
     *
     * @return string
     */
    public function getLocalization()
    {
        return $this->localization;
    }
}
