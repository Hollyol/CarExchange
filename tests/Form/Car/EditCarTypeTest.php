<?php

namespace App\Tests\Form\Car;

use Symfony\Component\Form\Test\TypeTestCase;

use App\Form\Car\EditCarType;
use App\Entity\Car;

class EditCarTypeTest extends TypeTestCase
{
	public function testFieldsRemoved()
	{
		$form = $this->factory->create(EditCarType::class);

		$this->assertFalse($form->has('brand'));
		$this->assertFalse($form->has('model'));
		$this->assertFalse($form->has('sits'));
		$this->assertFalse($form->has('fuel'));
	}

	public function testSubmitData()
	{
		$car = new Car();
		$car->setDescription('A nice Car');

		$form = $this->factory->create(EditCarType::class);
		$form->submit(['description' => 'A nice Car']);

		$this->assertTrue($form->isSynchronized());
		$this->assertEquals($car->getDescription(), $form->getData()->getDescription());

		$children  = $form->createView()->children;
		$this->assertArrayHasKey('description', $children);
	}
}
