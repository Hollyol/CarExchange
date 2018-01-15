<?php

namespace App\Tests\Validation;

use App\Entity\Location;

use Symfony\Component\Validator\Validation;
use PHPUnit\Framework\TestCase;

class LocationValidationTest extends TestCase
{
	protected static $validator;

	public static function setUpBeforeClass()
	{
		self::$validator = Validation::createValidatorBuilder()
			->enableAnnotationMapping()
			->getValidator();
	}

	public function testValidLocation()
	{
		$location = new Location();
		$location->setCountry('FR');
		$location->setState('Alsace');
		$location->setTown('Strasbourg');

		$errors = self::$validator->validate($location);
		$this->assertEmpty($errors);

		return ($location);
	}

	/**
	 * @dataProvider singleErrorProvider
	 */
	public function testSingleError(array $locationData, string $expectedOrigin)
	{
		$location = new Location();
		$location->setCountry($locationData['country']);
		$location->setState($locationData['state']);
		$location->setTown($locationData['town']);

		$errors = self::$validator->validate($location);

		$this->assertCount(1, $errors);
		$this->assertEquals($expectedOrigin, $errors[0]->getPropertyPath());
	}

	public function singleErrorProvider()
	{
		return array(
			'no country provided' => array(
				[
					'country' => '',
					'state' => 'Alsace',
					'town' => 'Strasbourg',
				],
				'country',
			),
			'not a valid country' => array(
				[
					'country' => 'Not Valid',
					'state' => 'Alsace',
					'town' => 'Strasbourg',
				],
				'country',
			),
			'state name too long' => array(
				[
					'country' => 'FR',
					'state' => 'This state name is too long and will trigger a validation error',
					'town' => 'Strasbourg',
				],
				'state',
			),
			'no town provided' => array(
				[
					'country' => 'FR',
					'state' => 'Alsace',
					'town' => '',
				],
				'town',
			),
			'town name too long' => array(
				[
					'country' => 'FR',
					'state' => 'Alsace',
					'town' => 'This town name is far too long and should trigger an error while beiing validated, otherwise i would be embarrassed. Really.'
				],
				'town',
			),
		);
	}
}
