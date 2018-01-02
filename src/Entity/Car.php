<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Car
 *
 * @ORM\Table(name="car")
 * @ORM\Entity(repositoryClass="App\Repository\CarRepository")
 */
class Car
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
     * @ORM\Column(name="brand", type="string", length=100)
	 * @Assert\Length(max = "100")
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=50)
	 * @Assert\Length(max = "50")
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="sits", type="integer")
	 * @Assert\GreaterThan(
	 * 	value = 0,
	 * 	message = "sits.lesser_than_one",
	 * )
     */
    private $sits;

    /**
     * @var string
     *
     * @ORM\Column(name="fuel", type="string", length=50, nullable=true)
	 * @Assert\Length(max = "50")
     */
    private $fuel;

	//BUILDER

	/**
	 * builds the entity
	 */
	public function __construct(array $attr = [])
	{
		$this->hydrate($attr);
	}

	//CUSTOMS

	/**
	 * hydrate
	 * hydrate the car
	 *
	 * @param array
	 */
	public function hydrate(array $attr)
	{
		foreach ($attr as $key => $value)
		{
			$method = 'set' . ucfirst($key);
			if (method_exists($this, $method)){
				$this->$method($value);
			}
		}
	}

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
     * Set brand
     *
     * @param string $brand
     *
     * @return Car
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return Car
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Car
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
     * Set sits
     *
     * @param integer $sits
     *
     * @return Car
     */
    public function setSits($sits)
    {
        $this->sits = $sits;

        return $this;
    }

    /**
     * Get sits
     *
     * @return int
     */
    public function getSits()
    {
        return $this->sits;
    }

    /**
     * Set fuel
     *
     * @param string $fuel
     *
     * @return Car
     */
    public function setFuel($fuel)
    {
        $this->fuel = $fuel;

        return $this;
    }

    /**
     * Get fuel
     *
     * @return string
     */
    public function getFuel()
    {
        return $this->fuel;
    }
}
