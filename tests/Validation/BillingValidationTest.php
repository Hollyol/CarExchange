<?php

namespace App\Tests\Validation;

use App\Entity\Billing;

use Symfony\Component\Validator\Validation;
use PHPUnit\Framework\TestCase;

class BillingValidationTest extends TestCase
{
	protected static $validator;

	public static function setUpBeforeClass()
	{
		self::$validator = Validation::createValidatorBuilder()
			->enableAnnotationMapping()
			->getValidator();
	}

	public function testValidBilling()
	{
		$billing = new Billing();
		$billing->setCurrency('EUR');
		$billing->setPrice(10);
		$billing->setTimeBase('day');

		$errors = self::$validator->validate($billing);
		$this->assertEmpty($errors);
	}

	/**
	 * @dataProvider singleErrorProvider
	 */
	public function testSingleError(array $billingData, string $expectedOrigin)
	{
		$billing = new Billing();
		$billing->setCurrency($billingData['currency']);
		$billing->setPrice($billingData['price']);
		$billing->setTimeBase($billingData['timeBase']);

		$errors = self::$validator->validate($billing);

		$this->assertCount(1, $errors);
		$this->assertEquals($expectedOrigin, $errors[0]->getPropertyPath());
	}

	public function singleErrorProvider()
	{
		return array(
			'not a valid currency' => array(
				[
					'currency' => 'notValid',
					'price' => 10,
					'timeBase' => 'day',
				],
				'currency',
			),
			'no currency provided' => array(
				[
					'currency' => '',
					'price' => 10,
					'timeBase' => 'day',
				],
				'currency',
			),
			'no price provided' => array(
				[
					'currency' => 'USD',
					'price' => '',
					'timeBase' => 'day',
				],
				'price',
			),
			'timeBase too long' => array(
				[
					'currency' => 'CHF',
					'price' => 10,
					'timeBase' => 'Not a valid time base',
				],
				'timeBase',
			),
			'no timeBase provided' => array(
				[
					'currency' => 'CAD',
					'price' => 10,
					'timeBase' => '',
				],
				'timeBase',
			),
		);
	}
}
