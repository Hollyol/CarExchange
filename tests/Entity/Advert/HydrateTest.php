<?php

namespace App\Tests\Entity\Advert;

use PHPUnit\Framework\TestCase;

use App\Entity\Advert;
use App\Entity\Car;
use App\Entity\Location;
use App\Entity\Billing;
use App\Entity\Member;

class HydrateTest extends TestCase
{
	/**
	 *
	 * If an attribute is provided in the array, the value of the
	 * attribute will be updated in object. If not, it should not be affected
	 *
	 * Hydratation never affects the list of rentals
	 *
	 * @dataProvider hydratationProvider
	 */
	public function testFirstHydratation(array $attributes, Advert $baseAdvert)
	{
		$advert = clone $baseAdvert;
		$advert->hydrate($attributes);

		if (array_key_exists('car', $attributes)) {
			$this->assertSame($attributes['car'], $advert->getCar());
		} else {
			$this->assertSame($baseAdvert->getCar(), $advert->getCar());
		}

		if (array_key_exists('location', $attributes)) {
			$this->assertSame($attributes['location'], $advert->getLocation());
		} else {
			$this->assertSame($baseAdvert->getLocation(), $advert->getLocation());
		}

		if (array_key_exists('billing', $attributes)) {
			$this->assertSame($attributes['billing'], $advert->getBilling());
		} else {
			$this->assertSame($baseAdvert->getBilling(), $advert->getBilling());
		}

		if (array_key_exists('owner', $attributes)) {
			$this->assertSame($attributes['owner'], $advert->getOwner());
		} else {
			$this->assertSame($baseAdvert->getOwner(), $advert->getOwner());
		}
		if (array_key_exists('beginDate', $attributes)) {
			$this->assertSame($attributes['beginDate'], $advert->getBeginDate());
		} else {
			$this->assertSame($baseAdvert->getBeginDate(), $advert->getBeginDate());
		}

		if (array_key_exists('endDate', $attributes)) {
			$this->assertSame($attributes['endDate'], $advert->getEndDate());
		} else {
			$this->assertSame($baseAdvert->getEndDate(), $advert->getEndDate());
		}

		if (array_key_exists('title', $attributes)) {
			$this->assertSame($attributes['title'], $advert->getTitle());
		} else {
			$this->assertSame($baseAdvert->getTitle(), $advert->getTitle());
		}

		$this->assertEmpty($advert->getRentals());
	}

	public function hydratationProvider()
	{
		$car = new Car();
		$car->setBrand('brand');
		$location = new Location();
		$location->setTown('town');
		$billing = new Billing();
		$billing->setPrice(23);
		$owner = new Member();
		$owner->setUsername('user');

		$advert = new Advert();
		$advert->setOwner($owner);
		$advert->setLocation($location);
		$advert->setCar($car);
		$advert->setBilling($billing);
		$advert->setBeginDate(new \Datetime('2016-03-21'));
		$advert->setEndDate(new \Datetime('2016-05-21'));
		$advert->setTitle('Default');

		$car2 = new Car();
		$location2 = new Location();
		$billing2 = new Billing();
		$owner2 = new Member();

		return array(
			'Everything is provided' => array(
				[
					'car' => $car,
					'location' => $location,
					'billing' => $billing,
					'owner' => $owner,
					'beginDate' => new \Datetime('2018-09-23'),
					'endDate' => new \Datetime('2020-04-30'),
					'title' => 'Great Title',
				],
				$advert,

			),
			'Only other entities' => array(
				[
					'car' => $car,
					'location' => $location,
					'billing' => $billing,
					'owner' => $owner,
				],
				$advert,
			),
			'Only native types' => array(
				[
					'beginDate' => new \Datetime('2018-09-23'),
					'endDate' => new \Datetime('2018-04-30'),
					'title' => 'Great Title',
				],
				$advert,
			),
		);
	}
}
