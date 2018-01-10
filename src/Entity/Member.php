<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * member
 *
 * @ORM\Table(name="member")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 *
 * @UniqueEntity(fields = "username", errorPath = "username", message = "username.already_used")
 * @UniqueEntity(fields = "mail", errorPath = "mail", message = "mail.already_used")
 * @UniqueEntity(fields = "phone", errorPath = "phone", message = "phone.already_used")
 */
class Member implements UserInterface, \Serializable
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
	 * @ORM\OneToMany(targetEntity="App\Entity\Advert", mappedBy="owner", cascade={"remove"})
	 */
	private $adverts;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Rental", mappedBy="renter", cascade={"remove"})
	 */
	private $rentals;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Location", cascade={"persist"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $location;

	/**
	 * @var string
	 *
	 * @Assert\Language()
	 *
	 * @ORM\Column(name="language", type="string", length=10, nullable=false)
	 */
	private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, nullable=false, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=15, unique=true)
     */
    private $phone;

    /**
     * @var string
     *
	 * @Assert\Email()
     * @ORM\Column(name="mail", type="string", length=100, nullable=false, unique=true)
     */
    private $mail;

	/**
	 * @var array
	 *
	 * @ORM\Column(name="roles", type="array", length=50)
	 */
	private $roles;

	//CALLBACKS

	/**
	 * @ORM\PrePersist
	 */
	public function setDefaultRoles()
	{
		if (!count($this->getRoles()))
		{
			$this->setRoles(array('ROLE_USER'));
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
	 * Get salt
	 *
	 * @return salt
	 */
	public function getSalt()
	{
		return null;
	}

	/**
	 * Set roles
	 *
	 * @return App\Entity\Member
	 */
	public function setRoles(array $roles)
	{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * Get roles
	 *
	 * @return array
	 */
	public function getRoles()
	{
		return $this->roles;
	}

	/**
	 * Erase credentials
	 */
	public function eraseCredentials()
	{
	}

	/**
	 * Serialize entity
	 *
	 * @see |Serializable::serialize()
	 * @return string
	 */
	public function serialize()
	{
		return serialize(array(
			$this->id,
			$this->username,
			$this->password,
		));
	}

	/**
	 * Unserialize entity
	 *
	 * @see |Serializable::unserialize()
	 * @param $serialized
	 *
	 * @return array
	 */
	public function unserialize($serialized)
	{
		return list(
			$this->id,
			$this->username,
			$this->password,
		) = unserialize($serialized);
	}
				
    /**
     * Set password
     *
     * @param string $password
     *
     * @return member
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return member
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return member
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return member
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Add advert
     *
     * @param \App\Entity\Advert $advert
     *
     * @return Member
     */
    public function addAdvert(\App\Entity\Advert $advert)
    {
        $this->adverts[] = $advert;
		$advert->setOwner($this);

        return $this;
    }

    /**
     * Remove advert
     *
     * @param \App\Entity\Advert $advert
     */
    public function removeAdvert(\App\Entity\Advert $advert)
    {
        $this->adverts->removeElement($advert);
    }

    /**
     * Get adverts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdverts()
    {
        return $this->adverts;
    }

    /**
     * Add rental
     *
     * @param \App\Entity\Rental $rental
     *
     * @return Member
     */
    public function addRental(\App\Entity\Rental $rental)
    {
        $this->rentals[] = $rental;
		$rental->setRenter($this);

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
     * Set locale
     *
     * @param string $locale
     *
     * @return Member
     */
    public function setLanguage($locale)
    {
        $this->language = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set location
     *
     * @param \App\Entity\Location $location
     *
     * @return Member
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
}
