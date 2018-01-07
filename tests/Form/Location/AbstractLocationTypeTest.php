<?php

namespace App\Tests\Form\Location;

use Symfony\Component\Form\Test\TypeTestCase;

use App\Form\Location\AbstractLocationType;
use App\Entity\Location;

class AbstractLocationTypeTest extends TypeTestCase
{
	public function testSubmitValidData()
	{
		$formData = array(
			'country' => 'FR',
			'state' => 'Alsace',
			'town' => 'Strasbourg',
		);

		$form = $this->factory->create(AbstractLocationType::class);

		$location = new Location();
		$location->setCountry($formData['country']);
		$location->setState($formData['state']);
		$location->setTown($formData['town']);

		$form->submit($formData);
		$this->assertTrue($form->isSynchronized());
		$this->assertEquals($location, $form->getData());

		$children = $form->createView()->children;

		foreach (array_keys($formData) as $key) {
			$this->assertArrayHasKey($key, $children);
		}
	}
}
