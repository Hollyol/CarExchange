<?php

namespace App\Tests\Entity\Advert;

use App\Entity\Advert;
use App\Entity\Location;
use App\Entity\Car;

use PHPUnit\Framework\TestCase;

class DefaultTitleTest extends TestCase
{
	/**
	 * It MUST NOT do any thing if a title already set
	 *
	 * If not, it must set the title with the car 'brand' and 'model' if
	 * provided, and the town of the advert
	 *
	 * @dataProvider provider
	 */
	public function testDoSomething(string $title, array $car, array $location)
	{
		$mockedCar = $this->createMock(Car::class);
		$mockedCar->method('getBrand')
			->will($this->returnValue($car['brand']));
		$mockedCar->method('getModel')
			->will($this->returnValue($car['model']));

		$mockedLocation = $this->createMock(Location::class);
		$mockedLocation->method('getTown')
			->will($this->returnValue($location['town']));

		$advert = new Advert();
		$advert->setTitle($title);
		$advert->setCar($mockedCar);
		$advert->setLocation($mockedLocation);

		$advert->setDefaultTitle();

		if (strlen($title) == 0) {
			if (strlen($car['brand'])) {
				$this->assertContains($car['brand'], $advert->getTitle());
			}
			if (strlen($car['model'])) {
				$this->assertContains($car['model'], $advert->getTitle());
			}

			$this->assertContains($location['town'], $advert->getTitle());

		} else {
			$this->assertEquals($title, $advert->getTitle());
		}
	}

	public function provider()
	{
		return array(
			'only title provided' => array(
				'A title for an advert',
				[
					'brand' => '',
					'model' => '',
				],
				[
					'town' => '',
				],
			),
			'no title (brand, model and town provided)' => array(
				'',
				[
					'brand' => 'Peugeot',
					'model' => '208',
				],
				[
					'town' => 'Lille',
				],
			),
			'no title (brand and town provided)' => array(
				'',
				[
					'brand' => 'Peugeot',
					'model' => '',
				],
				[
					'town' => 'Lille',
				],
			),
			'no title (model and town provided)' => array(
				'',
				[
					'brand' => '',
					'model' => '208',
				],
				[
					'town' => 'Lille',
				],
			),
			'only town' => array(
				'',
				[
					'brand' => '',
					'model' => '',
				],
				[
					'town' => 'Lille',
				],
			),
		);
	}
}
