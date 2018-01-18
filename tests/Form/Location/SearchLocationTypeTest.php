<?php

namespace App\Tests\Form\Location;

use App\Form\Location\SearchLocationType;

use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class SearchLocationTypeTest extends TypeTestCase
{
	protected static $validator;

	public static function setUpBeforeClass()
	{
		self::$validator = Validation::createValidatorBuilder()
			->getValidator();
	}

	public function testRemovedFields()
	{
		$form = $this->factory->create(SearchLocationType::class);

		$form->submit(['country' => 'FR']);

		$errors = self::$validator->validate($form);
		$this->assertEmpty($errors);
	}
}
