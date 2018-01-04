<?php

namespace App\Tests\Entity\Advert;

use PHPUnit\Framework\TestCase;

use App\Entity\Advert;
use App\Entity\Rental;

class RentedTest extends TestCase
{
	/**
	 *
	 * If a match is found, isRented must return true
	 *
	 * @dataProvider matchProvider
	 */
	public function testMatch(\Datetime $beginDate, \Datetime $endDate, array $rentals)
	{
		$advert = new Advert();
		foreach ($rentals as $rental)
		{
			$mockedRental = $this->createMock(Rental::class);
			$mockedRental->method('getBeginDate')
				->will($this->returnValue(new \Datetime($rental['beginDate'])));
			$mockedRental->method('getEndDate')
				->will($this->returnValue(new \Datetime($rental['endDate'])));
			$advert->addRental($mockedRental);
		}

		$this->assertTrue($advert->isRented($beginDate, $endDate), 'A match was supposed to be found');
	}

	/**
	 * If no match are found, must return false
	 *
	 * @dataProvider noMatchProvider
	 */
	public function testNoMatch(\Datetime $beginDate, \Datetime $endDate, array $rentals)
	{
		$advert = new Advert();
		foreach ($rentals as $rental)
		{
			$mockedRental = $this->createMock(Rental::class);
			$mockedRental->method('getBeginDate')
				->will($this->returnValue(new \Datetime($rental['beginDate'])));
			$mockedRental->method('getEndDate')
				->will($this->returnValue(new \Datetime($rental['endDate'])));
			$advert->addRental($mockedRental);
		}

		$this->assertFalse($advert->isRented($beginDate, $endDate), 'No match were supposed to be found');
	}

	public function matchProvider()
	{
		$rental1 = array(
			'beginDate' => '2018-01-04',
			'endDate' => '2018-02-22'
		);
		$rental2 = array(
			'beginDate' => '2018-04-12',
			'endDate' => '2018-05-18'
		);

		return array(
			'beginDate matching' => array(
				new \Datetime('2018-01-19'),
				new \Datetime('2018-03-19'),
				[
					$rental1,
				]
			),
			'endDate matching' => array(
				new \Datetime('2018-01-02'),
				new \Datetime('2018-02-19'),
				[
					$rental1,
				]
			),
			'both matchind different rentals' => array(
				new \Datetime('2018-02-13'),
				new \Datetime('2018-04-25'),
				[
					$rental1,
					$rental2,
				]
			),
			'including a rental' => array(
				new \Datetime('2018-04-09'),
				new \Datetime('2018-05-20'),
				[
					$rental2,
				]
			),
		);
	}

	public function noMatchProvider()
	{
		$rental1 = array(
			'beginDate' => '2018-01-04',
			'endDate' => '2018-02-22'
		);
		$rental2 = array(
			'beginDate' => '2018-04-12',
			'endDate' => '2018-05-18'
		);

		return array(
			'before rentals' => array(
				new \Datetime('2017-01-19'),
				new \Datetime('2017-03-19'),
				[
					$rental1,
				]
			),
			'after rentals' => array(
				new \Datetime('2019-01-02'),
				new \Datetime('2019-02-19'),
				[
					$rental1,
				]
			),
			'between two rentals' => array(
				new \Datetime('2018-02-26'),
				new \Datetime('2018-03-12'),
				[
					$rental1,
					$rental2,
				]
			),
		);
	}
}
