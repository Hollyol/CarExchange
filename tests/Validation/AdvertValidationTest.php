<?php

namespace App\Tests\Validation;

use App\Entity\Advert;
use App\Entity\Billing;
use App\Entity\Location;
use App\Entity\Member;
use App\Entity\Car;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;

class AdvertValidationTest extends KernelTestCase
{
	protected static $validator;

	public static function setUpBeforeClass()
	{
		self::$validator = static::bootKernel()
			->getContainer()
			->get('validator');
	}

	public function testValidAdvert()
	{
		$location = new Location();
		$location->setCountry('FR');
		$location->setState('Alsace');
		$location->setTown('Strasbourg');

		$billing = new Billing();
		$billing->setCurrency('EUR');
		$billing->setPrice(10);
		$billing->setTimeBase('day');

		$car = new Car();
		$car->setBrand('Peugeot');
		$car->setModel('307');
		$car->setSits(5);
		$car->setFuel('diesel');

		$owner = new Member();
		$owner->setUsername('Hollyol');
		$owner->setPassword('fake');
		$owner->setMail('hollyol@mail.com');
		$owner->setPhone('09 09 09 09 09');
		$owner->setLanguage('fr');
		$owner->setLocation($location);

		$advert = new Advert();
		$advert->setBeginDate(new \Datetime("today"));
		$advert->setEndDate(new \Datetime("tomorrow"));
		$advert->setTitle('A Great Title');
		$advert->setCar($car);
		$advert->setLocation($location);
		$advert->setOwner($owner);
		$advert->setBilling($billing);

		$errors = self::$validator->validate($advert);

		$this->assertEmpty($errors);
	}

	/**
	 * @dataProvider singleErrorProvider
	 */
	public function testSingleError(array $advertData, string $expectedOrigin)
	{
		$advert = new Advert();
		$advert->setTitle($advertData['title']);
		$advert->setBeginDate($advertData['beginDate']);
		$advert->setEndDate($advertData['endDate']);
		$advert->setCar($advertData['car']);
		$advert->setBilling($advertData['billing']);
		$advert->setLocation($advertData['location']);
		$advert->setOwner($advertData['owner']);

		$errors = self::$validator->validate($advert);

		$this->assertCount(1, $errors);
		$this->assertEquals($expectedOrigin, $errors[0]->getPropertyPath());
	}

	public function testObjectsNotProvided()
	{
		$advert = new Advert();

		$errors = self::$validator->validate($advert);
		$this->assertCount(4, $errors);

		foreach ($errors as $error) {
			$origin[] = $error->getPropertyPath();
		}

		$this->assertTrue(in_array('car', $origin));
		$this->assertTrue(in_array('location', $origin));
		$this->assertTrue(in_array('owner', $origin));
		$this->assertTrue(in_array('billing', $origin));
	}

	public function singleErrorProvider()
	{
		$location = new Location();
		$location->setCountry('FR');
		$location->setState('Alsace');
		$location->setTown('Strasbourg');

		$billing = new Billing();
		$billing->setCurrency('EUR');
		$billing->setPrice(10);
		$billing->setTimeBase('day');

		$owner = new Member();
		$owner->setUsername('Hollyol');
		$owner->setPassword('fake');
		$owner->setMail('hollyol@mail.com');
		$owner->setPhone('09 09 09 09 09');
		$owner->setLanguage('fr');
		$owner->setLocation($location);

		$car = new Car();
		$car->setBrand('Peugeot');
		$car->setModel('307');
		$car->setFuel('diesel');
		$car->setSits(5);

		return array(
			'title too long' => array(
				[
					'title' => 'This title is far too long and should trigger an error while beiing validated. Otherwise i would be embarrassed. Really.',
					'beginDate' => new \Datetime('today'),
					'endDate' => new \Datetime('tomorrow'),
					'location' => $location,
					'car' => $car,
					'billing' => $billing,
					'owner' => $owner,
				],
				'title',
			),
			'negative duration' => array(
				[
					'title' => 'A great title',
					'beginDate' => new \Datetime('tomorrow'),
					'endDate' => new \Datetime('today'),
					'location' => $location,
					'car' => $car,
					'billing' => $billing,
					'owner' => $owner,
				],
				'endDate',
			),
		);
	}
}
