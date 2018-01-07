<?php

namespace App\Tests\Form\Car;

use App\Form\Car\AbstractCarType;
use App\Entity\Car;

use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validation;

class AbstractCarTypeTest extends TypeTestCase
{
	private $validator;

	//If some validation is needed in the form
	protected function getExtensions()
	{
		$this->validator = $this->createMock(ValidatorInterface::class);

		$this->validator
			->method('validate')
			->will($this->returnValue(new ConstraintViolationList()));

		$this->validator
			->method('getMetaDataFor')
			->will($this->returnValue(new ClassMetadata(Form::class)));

		return array(
			new ValidatorExtension($this->validator)
		);
	}

	//The form must be submitted correctly, map the values
	//and create relevant view
	/**
	 * @dataProvider provider
	 */
	public function testSubmitValidData(array $formData)
	{
		//test if the form compiles
		$form = $this->factory->create(AbstractCarType::class);

		$car = new Car($formData);

		$form->submit($formData);

		//test data transformers exceptions
		$this->assertTrue($form->isSynchronized());
		//test if the data is correctly setted
		$this->assertEquals($car, $form->getData());


		$view = $form->createView();
		$children = $view->children;

		foreach(array_keys($formData) as $key){
			$this->assertArrayHasKey($key, $children);
		}
	}

	//PROVIDERS

	public function provider()
	{
		return array(
			'valid car' => array(
				[
					'brand' => 'Peugeot',
					'model' => '307',
					'sits' => 5,
					'fuel' => 'diesel',
					'description' => 'Nice car.',
				]
			),
			'sits and fuel not provided' => array(
				[
					'brand' => 'Peugeot',
					'model' => '307',
					'description' => 'Nice car.',
				]
			),
		);
	}
}
