<?php

namespace App\Tests\Validation;

use App\Entity\Car;

use Symfony\Component\Validator\Validation;
use PHPUnit\Framework\TestCase;

class CarValidationTest extends TestCase
{
	protected static $validator;

	public static function setUpBeforeClass()
	{
		self::$validator = Validation::createValidatorBuilder()
			->enableAnnotationMapping()
			->getValidator();
	}

	public function testValidCar()
	{
		$car = new Car();
		$car->setBrand('Peugeot');
		$car->setModel('307');
		$car->setSits(5);
		$car->setFuel('diesel');
		$car->setDescription('Nice car');

		$errors = self::$validator->validate($car);
		$this->assertEmpty($errors);
	}

	/**
	 * @dataProvider carProvider
	 */
	public function testSingleError(array $carData, string $expectedOrigin)
	{
		$car = new Car($carData);
		$errors = self::$validator->validate($car);

		$this->assertCount(1, $errors);
		$this->assertEquals($expectedOrigin, $errors[0]->getPropertyPath());
	}

	public function carProvider()
	{
		return array(
			'brand name too long' => array(
				[
					'brand' => 'This brand name is way too long and should trigger an error while beeing validate. Otherwise, i would embarrassed. Really.',
					'model' => '307',
					'sits' => 5,
					'fuel' => 'diesel',
					'description' => 'Nice car',
				],
				'brand'
			),
			'model name too long' => array(
				[
					'brand' => 'Peugeot',
					'model' => 'This model name is too long sould trigger an error at validation',
					'sits' => 5,
					'fuel' => 'diesel',
					'description' => 'Nice car',
				],
				'model',
			),
			'not enought sits' => array(
				[
					'brand' => 'Peugeot',
					'model' => '307',
					'sits' => 0,
					'fuel' => 'diesel',
					'description' => 'Nice car',
				],
				'sits',
			),
			'fuel name too long' => array(
				[
					'brand' => 'Peugeot',
					'model' => '307',
					'sits' => 5,
					'fuel' => 'Thid fuel is too long and should trigger an error at validation',
					'description' => 'Nice car',
				],
				'fuel',
			),
		);
	}
}
