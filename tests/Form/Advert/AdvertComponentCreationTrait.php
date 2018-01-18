<?php

namespace App\Tests\Form\Advert;

use App\Entity\Member;
use App\Entity\Location;
use App\Entity\Billing;
use App\Entity\Car;

trait AdvertComponentCreationTrait
{
	public function createValidOwner()
	{
		$owner = new Member();
		$owner->setUsername('Hollyol');
		$owner->setPassword('password');
		$owner->setMail('hollyol@mail.com');
		$owner->setPhone('09 09 09 09 09');
		$owner->setLanguage('fr');
		$owner->setLocation($this->createValidLocation());

		return ($owner);
	}

	public function createValidLocation()
	{
		$location = new Location();
		$location->setCountry('FR');
		$location->setState('Alsace');
		$location->setTown('Strasbourg');

		return ($location);
	}

	public function createValidLocationArray()
	{
		return array(
			'country' => 'FR',
			'state' => 'Alsace',
			'town' => 'Strasbourg',
		);
	}

	public function createValidBilling()
	{
		$billing = new Billing();
		$billing->setCurrency('EUR');
		$billing->setPrice(10);
		$billing->setTimeBase('day');

		return ($billing);
	}

	public function createValidBillingArray()
	{
		return array(
			'currency' => 'EUR',
			'price' => 10,
			'timeBase' => 'day',
		);
	}

	public function createValidCar()
	{
		$car = new Car();
		$car->setBrand('Peugeot');
		$car->setModel('307');
		$car->setFuel('diesel');
		$car->setSits(5);

		return ($car);
	}

	public function createValidCarArray()
	{
		return array(
			'brand' => 'Peugeot',
			'model' => '307',
			'fuel' => 'diesel',
			'sits' => 5,
		);
	}
}
