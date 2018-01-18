<?php

namespace App\Tests\Form\Advert;

use App\Form\Advert\SearchAdvertType;

use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SearchAdvertTypeTest extends TypeTestCase
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

	public function testFieldsRemoved()
	{
		$form = $this->factory->create(SearchAdvertType::class);

		$this->assertFalse($form->has('title'));
		$this->assertFalse($form->has('billing'));
	}
}
