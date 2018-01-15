<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Billing
 *
 * @ORM\Table(name="billing")
 * @ORM\Entity(repositoryClass="App\Repository\BillingRepository")
 */
class Billing
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Currency", type="string", length=10)
	 *
	 * @Assert\NotBlank()
	 * @Assert\Currency()
     */
    private $currency;

    /**
     * @var int
     *
     * @ORM\Column(name="Price", type="integer")
	 *
	 * @Assert\NotBlank();
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="TimeBase", type="string", length=15)
	 *
	 * @Assert\NotBlank()
	 * @Assert\Length(max=15)
     */
    private $timeBase;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return Billing
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Billing
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set timeBase
     *
     * @param string $timeBase
     *
     * @return Billing
     */
    public function setTimeBase($timeBase)
    {
        $this->timeBase = $timeBase;

        return $this;
    }

    /**
     * Get timeBase
     *
     * @return string
     */
    public function getTimeBase()
    {
        return $this->timeBase;
    }
}

