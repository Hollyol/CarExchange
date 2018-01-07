<?php

namespace App\Tests\Form\Rental;

use Symfony\Component\Form\Test\TypeTestCase;

use App\Form\Rental\AbstractRentalType;
use App\Entity\Rental;

use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AbstractRentalTypeTest extends TypeTestCase
{
	private $validator;

	public function getExtensions()
	{
		$this->validator = $this->createMock(ValidatorInterface::class);

		$this->validator
			->method('validate')
			->will($this->returnValue(new ConstraintViolationList()));
		$this->validator
			->method('getMetadataFor')
			->will($this->returnValue(new ClassMetadata(Form::class)));

		return array(
			new ValidatorExtension($this->validator),
		);
	}

	public function testSubmitedData()
	{
		$formData = array(
			'beginDate' => [
				'year' => 2018,
				'month' => 01,
				'day' => 01,
			],
			'endDate' => [
				'year' => 2018,
				'month' => 01,
				'day' => 01,
			],
		);

		$rental = new Rental();
		$rental->setBeginDate(new \Datetime('2018-01-01'));
		$rental->setEndDate(new \Datetime('2018-01-01'));

		$form = $this->factory->create(AbstractRentalType::class);

		$form->submit($formData);

		$this->assertTrue($form->isSynchronized());
		$this->assertEquals($rental, $form->getData());

		$children = $form->createView()->children;
		foreach (array_keys($formData) as $key) {
			$this->assertArrayHasKey($key, $children);
		}
	}
}
