<?php

namespace App\Tests\Validation;

use App\Entity\Rental;
use App\Entity\Advert;
use App\Entity\Member;
use App\Entity\Location;
use App\Entity\Car;
use App\Entity\Billing;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;

class RentalValidationTestCase extends KernelTestCase
{
	protected static $validator;

	public static function setUpBeforeClass()
	{
		self::$validator = static::bootKernel()
			->getContainer()
			->get('validator');
	}

	public function testValidRental()
	{
		$rental = new Rental();
		$rental->setAdvert($this->createValidAdvert());
		$rental->setRenter($this->createValidRenter());
		$rental->setBeginDate(new \Datetime('2018-01-03'));
		$rental->setEndDate(new \Datetime('2018-01-12'));

		$errors = self::$validator->validate($rental);

		$this->assertEmpty($errors);
	}

	/**
	 * @dataProvider singleErrorProvider
	 */
	public function testSingleError(array $rentalData, string $expectedOrigin)
	{
		$rental = new Rental();
		$rental->setAdvert($this->createValidAdvert());
		$rental->setRenter($this->createValidRenter());
		$rental->setBeginDate($rentalData['beginDate']);
		$rental->setEndDate($rentalData['endDate']);

		$errors = self::$validator->validate($rental);

		$this->assertCount(1, $errors);
		$this->assertEquals($expectedOrigin, $errors[0]->getPropertyPath());
	}

	public function singleErrorProvider()
	{
		return array(
			'not during advert validity period' => array(
				[
					'beginDate' => new \Datetime('2017-01-01'),
					'endDate' => new \Datetime('2017-01-07'),
				],
				'beginDate',
			),
			'negative duration' => array(
				[
					'beginDate' => new \Datetime('2018-01-12'),
					'endDate' => new \Datetime('2018-01-05'),
				],
				'endDate',
			),
		);
	}

	public function createValidRenter()
	{
		$location = new Location();
		$location->setCountry('FR');
		$location->setState('Nord');
		$location->setTown('Lille');

		$renter = new Member();
		$renter->setUsername('Woliro');
		$renter->setPassword('fake');
		$renter->setMail('woliro@mail.com');
		$renter->setPhone('08 08 08 08 08');
		$renter->setLanguage('fr');
		$renter->setLocation($location);

		return ($renter);
	}

	public function createValidAdvert()
	{
		$location = new Location();
		$location->setCountry('FR');
		$location->setState('Alsace');
		$location->setTown('Strasbourg');

		$owner = new Member();
		$owner->setUsername('Hollyol');
		$owner->setPassword('fake');
		$owner->setMail('hollyol@mail.com');
		$owner->setPhone('09 09 09 09 09');
		$owner->setLanguage('fr');
		$owner->setLocation($location);

		$billing = new Billing();
		$billing->setCurrency('EUR');
		$billing->setPrice(10);
		$billing->setTimeBase('day');

		$car = new Car();
		$car->setBrand('Peugeot');
		$car->setModel('308');
		$car->setFuel('diesel');
		$car->setSits(5);

		$advert = new Advert();
		$advert->setBeginDate(new \Datetime('2018-01-01'));
		$advert->setEndDate(new \Datetime('2018-01-30'));
		$advert->setOwner($owner);
		$advert->setLocation($location);
		$advert->setBilling($billing);
		$advert->setCar($car);

		return ($advert);
	}
}
