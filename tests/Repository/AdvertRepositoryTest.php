<?php

namespace App\Tests\Repositoy;

use App\Entity\Advert;
use App\Entity\Location;
use App\Entity\Member;
use App\Entity\Car;
use App\Entity\Billing;
use App\Entity\Rental;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AdvertRepositoryTest extends KernelTestCase
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	protected function setUp()
	{
		$this->em = self::bootKernel()->getContainer()
			->get('doctrine')
			->getManager();

		$this->em->beginTransaction();
		$this->createDataSet();
	}

	protected function tearDown()
	{
		$this->cleanDataSet();
		parent::tearDown();

		$this->em->close();
		$this->em = null;
	}

	/**
	 * @dataProvider carProvider
	 */
	public function testWhereCarLike(Car $car, int $expected)
	{
		$repos = $this->em->getRepository(Advert::class);
		$qb = $repos->createQueryBuilder('a');
		$repos->whereCarLike($qb, $car);

		$this->assertCount($expected, $qb->getQuery()->getResult());
	}

	/**
	 * @dataProvider rentalDateProvider
	 */
	public function testHasValidRentalPeriods($beginDate, $endDate, int $expected)
	{
		$repos = $this->em->getRepository(Advert::class);
		$qb = $repos->createQueryBuilder('a');
		$repos->hasValidPeriod($qb, $beginDate, $endDate);

		$this->assertCount($expected, $qb->getQuery()->getResult());
	}

	/**
	 * @dataProvider locationProvider
	 */
	public function testWhereLocationIs($country, $state, $town, $expected)
	{
		if (!$country)
			$this->expectException(\Symfony\Component\HttpKernel\Exception\PreconditionRequiredHttpException::class);
		$repos = $this->em->getRepository(Advert::class);
		$qb = $repos->createQueryBuilder('a');
		$repos->whereCountryIs($qb, $country);
		$repos->whereStateIs($qb, $state);
		$repos->whereTownIs($qb, $town);

		$result = $qb->getQuery()->getResult();

		if (count($result) == 1) {
			$this->assertEquals('An other title', $result[0]->getTitle());
		} else if (count($result) == 2) {
			$this->assertEquals('A Great Title', $result[0]->getTitle());
			$this->assertEquals('An other title', $result[1]->getTitle());
		}

		$this->assertCount($expected, $result);
	}

	private function createDataSet()
	{
		$location_1 = new Location();
		$location_1->setCountry('FR');
		$location_1->setTown('Strasbourg');
		$location_1->setState('Alsace');

		$renter_1 = new Member();
		$renter_1->setUsername('Renter');
		$renter_1->setPassword('Renterpassword');
		$renter_1->setMail('renter@mail.com');
		$renter_1->setPhone('09 34 09 09 09');
		$renter_1->setLanguage('fr');
		$renter_1->setLocation($location_1);

		$car_1 = new Car();
		$car_1->setBrand('Peugeot');
		$car_1->setModel('307');
		$car_1->setSits(5);
		$car_1->setFuel('diesel');

		$billing_1 = new Billing();
		$billing_1->setCurrency('Euro');
		$billing_1->setPrice(10);
		$billing_1->setTimeBase('day');

		$rental_1 = new Rental();
		$rental_1->setBeginDate(new \Datetime('2018-04-12'));
		$rental_1->setEndDate(new \Datetime('2018-04-16'));
		$rental_1->setRenter($renter_1);
		$rental_1->setStatus('asking');

		$rental_2 = new Rental();
		$rental_2->setBeginDate(new \Datetime('2018-06-03'));
		$rental_2->setEndDate(new \Datetime('2018-07-10'));
		$rental_2->setRenter($renter_1);

		$owner = new Member();
		$owner->setUsername('Hollyol');
		$owner->setPassword('password');
		$owner->setMail('hollyol@mail.com');
		$owner->setPhone('09 09 09 09 09');
		$owner->setLanguage('fr');
		$owner->setLocation($location_1);

		$advert_1 = new Advert();
		$advert_1->setTitle('A Great Title');
		$advert_1->setBeginDate(new \Datetime('2018-01-01'));
		$advert_1->setEndDate(new \Datetime('2019-05-01'));
		$advert_1->setLocation($location_1);
		$advert_1->setCar($car_1);
		$advert_1->setBilling($billing_1);
		$advert_1->setOwner($owner);
		$advert_1->addRental($rental_1);
		$advert_1->addRental($rental_2);

		$car_2 = clone $car_1;
		$billing_2 = clone $billing_1;

		$advert_2 = new Advert();
		$advert_2->setTitle('An other title');
		$advert_2->setBeginDate(new \Datetime('2018-01-10'));
		$advert_2->setEndDate(new \Datetime('2019-05-10'));
		$advert_2->setLocation($location_1);
		$advert_2->setCar($car_2);
		$advert_2->setBilling($billing_2);
		$advert_2->setOwner($owner);

		$this->em->persist($rental_1);
		$this->em->persist($rental_2);
		$this->em->persist($renter_1);
		$this->em->persist($owner);
		$this->em->persist($advert_1);
		$this->em->persist($advert_2);
		$this->em->flush();
	}

	private function cleanDataSet()
	{
		$this->em->rollback();
	}

	public function locationProvider()
	{
		return array(
			'all provided and match' => [
				'FR',
				'Alsace',
				'Strasbourg',
				2,
			],
			'only country and match' => [
				'FR',
				'',
				'',
				2,
			],
			'country and state, match' => [
				'FR',
				'Alsace',
				'',
				2,
			],
			'country and town, match' => [
				'FR',
				'',
				'Strasbourg',
				2,
			],
			'country not match' => [
				'DE',
				'',
				'Strasbourg',
				0,
			],
			'state no match' => [
				'FR',
				'Lorraine',
				'Strasbourg',
				0,
			],
			'town no match' => [
				'FR',
				'Alsace',
				'Mulhouse',
				0,
			],
			'no country' => [
				'',
				'Alsace',
				'Strasbourg',
				0,
			],
		);
	}

	public function carProvider()
	{
		return array(
			'nothing provided' => [
				new Car(),
				2,
			],
			'sits provided and matching' => [
				new Car(['sits' => 5]),
				2,
			],
			'sits provided but not matching' => [
				new Car(['sits' => 23]),
				0,
			],
			'fuel provided and matching' => [
				new Car(['fuel' => 'diesel']),
				2,
			],
			'fuel provided but not matching' => [
				new Car(['fuel' => 'fake']),
				0,
			],
			'both provided and matching' => [
				new Car([
					'sits' => 5,
					'fuel' => 'diesel',
				]),
				2,
			],
			'both provided, none matching' => [
				new Car([
					'sits' => 23,
					'fuel' => 'fake',
				]),
				0,
			],
			'both provided, only fuel matching' => [
				new Car([
					'sits' => 23,
					'fuel' => 'diesel',
				]),
				0,
			],
			'both provided, only sits matching' => [
				new Car([
					'sits' => 5,
					'fuel' => 'fake',
				]),
				0,
			],
		);
	}

	public function rentalDateProvider()
	{
		return array(
			'out of validity range' => [
				new \Datetime('2015-03-01'),
				new \Datetime('2015-03-20'),
				0,
			],
			'beginDate in a rental' => [
				new \Datetime('2018-04-13'),
				new \Datetime('2018-04-18'),
				1,
			],
			'endDate in a reantal' => [
				new \Datetime('2018-04-10'),
				new \Datetime('2018-04-15'),
				1,
			],
			'both in same rental' => [
				new \Datetime('2018-04-13'),
				new \Datetime('2018-04-15'),
				1,
			],
			'both in different rentals' => [
				new \Datetime('2018-04-13'),
				new \Datetime('2018-06-12'),
				1,
			],
			'including a rental' => [
				new \Datetime('2018-04-10'),
				new \Datetime('2018-04-20'),
				1,
			],
			'both before any rentals' => [
				new \Datetime('2018-03-03'),
				new \Datetime('2018-03-10'),
				2,
			],
			'both after any rentals' => [
				new \Datetime('2018-08-03'),
				new \Datetime('2018-08-10'),
				2,
			],
			'between two rentals' => [
				new \Datetime('2018-04-25'),
				new \Datetime('2018-05-02'),
				2,
			],
		);
	}
}
