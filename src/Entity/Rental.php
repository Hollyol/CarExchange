<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Member;
use App\Entity\Advert;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Rental
 *
 * @ORM\Table(name="rental")
 * @ORM\Entity(repositoryClass="App\Repository\RentalRepository")
 */
class Rental
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
	 * @ORM\ManyToOne(targetEntity="App\Entity\Advert", inversedBy="rentals")
	 * @ORM\JoinColumn(nullable = false)
	 *
	 * @Assert\Valid()
	 */
	private $advert;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Member", inversedBy="rentals")
	 * @ORM\JoinColumn(nullable = false)
	 *
	 * @Assert\Valid()
	 */
	private $renter;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="beginDate", type="datetime")
	 *
	 * @Assert\GreaterThanOrEqual(value="today", message = "date.in_past")
	 * @Assert\NotBlank()
     */
    private $beginDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime")
	 *
	 * @Assert\GreaterThanOrEqual(value="today", message = "date.in_past")
	 * @Assert\NotBlank();
     */
    private $endDate;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="status", type="string", length = 25, nullable=false)
	 *
	 * @Assert\Length(max="25")
	 */
	private $status;

	//CUSTOMS

	/**
	 * Build Entity
	 */
	public function __construct()
	{
		$this->beginDate = new \DateTime();
		$this->endDate = new \DateTime();
		$this->status = 'nothing';
	}

	//VALIDATION CALLBACKS

	/**
	 * Check that dates are in the correct order
	 *
	 * @Assert\Callback
	 */
	public function validateEndDate(ExecutionContextInterface $context, $payload)
	{
		if ($this->getBeginDate() > $this->getEndDate()) {
			$context->buildViolation('date.negative_duration')
				->atPath('endDate')
				->addViolation();
		}
	}

	/**
	 * Check that the advert is available during the given period
	 *
	 * @Assert\Callback
	 */
	public function validateAvailability(ExecutionContextInterface $context, $payload)
	{
		if ($this->getAdvert()->isRented($this->getBeginDate(), $this->getEndDate())) {
			$context->buildViolation('advert.already_rented')
				->atPath('beginDate')
				->addViolation();
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
     * Set beginDate
     *
     * @param \DateTime $beginDate
     *
     * @return Rental
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
     * @return Rental
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
     * Set advert
     *
     * @param \App\Entity\Advert $advert
     *
     * @return Rental
     */
    public function setAdvert(\App\Entity\Advert $advert)
    {
        $this->advert = $advert;

        return $this;
    }

    /**
     * Get advert
     *
     * @return \App\Entity\Advert
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    /**
     * Set renter
     *
     * @param \App\Entity\Member $renter
     *
     * @return Rental
     */
    public function setRenter(\App\Entity\Member $renter)
    {
        $this->renter = $renter;

        return $this;
    }

    /**
     * Get renter
     *
     * @return \App\Entity\Member
     */
    public function getRenter()
    {
        return $this->renter;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Rental
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
