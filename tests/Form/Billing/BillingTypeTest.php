<?php

namespace App\Tests\Form;

use Symfony\Component\Form\Test\TypeTestCase;

use App\Form\Billing\BillingType;
use App\Entity\Billing;

class BillingTypeTest extends TypeTestCase
{
	/**
	 * @dataProvider provider
	 */
	public function testSubmitData(array $formData)
	{
		$billing = new Billing();
		$billing->setPrice($formData['price']);
		$billing->setCurrency($formData['currency']);
		$billing->setTimeBase($formData['timeBase']);
		$form = $this->factory->create(BillingType::class);

		$form->submit($formData);

		$this->assertTrue($form->isSynchronized());
		$this->assertEquals($billing, $form->getData());

		$children = $form->createView()->children;
		
		foreach (array_keys($formData) as $key) {
			$this->assertArrayHasKey($key, $children);
		}
	}

	public function provider()
	{
		return array(
			'everything provided' => array([
				'price' => 10,
				'currency' => 'Euro',
				'timeBase' => 'day',
			],
		),
		);
	}
}
