<?php

namespace App\Entity;

use App\Entity\Location;
use App\Entity\Member;
use App\Entity\Car;
use App\Entity\Billing;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Advert
 *
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="App\Repository\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Advert
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
	 * @ORM\OneToOne(targetEntity="App\Entity\Car", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable = false)
	 *
	 * @Assert\Valid()
	 */
	private $car;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Location", cascade={"persist"})
	 * @ORM\JoinColumn(nullable = false)
	 *
	 * @Assert\Valid()
	 */
	private $location;

	/**
	 * @ORM\OneToOne(targetEntity="App\Entity\Billing", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable = false)
	 *
	 * @Assert\Valid()
	 */
	private $billing;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Member", inversedBy="adverts")
	 * @ORM\JoinColumn(nullable = false)
	 *
	 * @Assert\Valid()
	 */
	private $owner;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Rental", mappedBy="advert", cascade={"remove"})
	 */
	private $rentals;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="beginDate", type="datetime")
	 * @Assert\GreaterThanOrEqual(value = "today", message = "date.in_past")
	 * @Assert\NotBlank()
     */
    private $beginDate;

    /**
     * @var \DateTime
     *
	 * @ORM\Column(name="endDate", type="datetime")
	 * @Assert\GreaterThanOrEqual(value = "today", message = "date.in_past")
	 * @Assert\NotBlank()
     */
    private $endDate;

    /**
     * @var string
     *
	 * @ORM\Column(name="title", type="string", length=100)
	 * @Assert\Length(max = "99", maxMessage = "title.too_long")
     */
    private $title;

	//CUSTOMS

	/**
	 * hydrate
	 * hydrate the advert
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
	 * Check if advert isRented during a period
	 *
	 * @param \Datetime beginDate
	 * @param \Datetime endDate
	 *
	 * @return bool
	 */
	public function isRented(\Datetime $beginDate, \Datetime $endDate)
	{
		$rentals = $this->getRentals();
		foreach($rentals as $rental){
			if (
				//If the beginDate is included in a rental
				(($rental->getBeginDate() < $beginDate)
					AND ($rental->getEndDate() > $beginDate))
				//If the endDate is included in a rental
				OR (($rental->getBeginDate() < $endDate)
					AND ($rental->getEndDate() > $endDate))
				//If the period includes a rental
				OR (($rental->getBeginDate() > $beginDate)
					AND ($rental->getEndDate() < $endDate))
			){
				return true;
			}
		}
		return false;
	}

	/**
	 * Check if advert is valid
	 *
	 * @param \Datetime $beginDate
	 * @param \Datetime $endDate
	 *
	 * @return bool
	 */
	public function isValid(\Datetime $beginDate, \Datetime $endDate)
	{
		if ($this->beginDate < $beginDate
			AND $beginData < $this->endDate
			AND $this->beginDate < $endDate
			AND $this->endDate > $endDate) {
			return true;
		}

		return false;
	}

	//LIFECYCLE_CALLBACKS

	/**
	 * @ORM\PrePersist
	 */
	public function setDefaultTitle()
	{
		if (!$this->getTitle()) {
			$brand = $this->getCar()->getBrand();
			$model = $this->getCar()->getModel();
			$town = $this->getLocation()->getTown();

			if ($brand) {
				$this->setTitle($brand . ' ');
			}

			if ($model) {
				$this->setTitle(
					$this->getTitle() .
					$model .
					' '
				);
			}

			$this->setTitle(
				$this->getTitle() .
				'In ' .
				$town
			);
		}
	}

	//VALIDATION_CALLBACKS

	/**
	 * @Assert\Callback
	 */
	public function validateEndDate(ExecutionContextInterface $context, $payload)
	{
		if ($this->getBeginDate() > $this->getEndDate()){
			$context->buildViolation('date.negative_duration')
				->atPath('endDate')
				->addViolation();
		}
	}

	/**
	 * Constructor
	 */
	public function __construct(array $attr = [])
	{
		$this->beginDate = new \Datetime();
		$this->endDate = new \Datetime();
		$this->hydrate($attr);
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
     * Set beginDate
     *
     * @param \DateTime $beginDate
     *
     * @return Advert
     */
    public function setBeginDate($beginDate)
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    /**
     * Get beginDate
     *
     * @return \DateTime
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Advert
     */
    public function setEndDate($endDate)
    {
		$this->endDate = $endDate;
        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set car
     *
     * @param \App\Entity\Car $car
     *
     * @return Advert
     */
    public function setCar(\App\Entity\Car $car)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * Get car
     *
     * @return \App\Entity\Car
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * Set owner
     *
     * @param \App\Entity\Member $owner
     *
     * @return Advert
     */
    public function setOwner(\App\Entity\Member $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \App\Entity\Member
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add rental
     *
     * @param \App\Entity\Rental $rental
     *
     * @return Advert
     */
    public function addRental(\App\Entity\Rental $rental)
    {
        $this->rentals[] = $rental;
		$rental->setAdvert($this);

        return $this;
    }

    /**
     * Remove rental
     *
     * @param \App\Entity\Rental $rental
     */
    public function removeRental(\App\Entity\Rental $rental)
    {
        $this->rentals->removeElement($rental);
    }

    /**
     * Get rentals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRentals()
    {
        return $this->rentals;
    }

    /**
     * Set location
     *
     * @param \App\Entity\Location $location
     *
     * @return Advert
     */
    public function setLocation(\App\Entity\Location $location)
    {
		$this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \App\Entity\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set billing
     *
     * @param \App\Entity\Billing $billing
     *
     * @return Advert
     */
    public function setBilling(\App\Entity\Billing $billing)
    {
        $this->billing = $billing;

        return $this;
    }

    /**
     * Get billing
     *
     * @return \App\Entity\Billing
     */
    public function getBilling()
    {
        return $this->billing;
    }
}
