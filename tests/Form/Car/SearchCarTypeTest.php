<?php

namespace App\Tests\Form\Car;

use App\Form\Car\SearchCarType;
use App\Entity\Car;

use App\Tests\Form\Car\AbstractCarTypeTest;

use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

/*
 * The SearchCarType extends to AbstractCarType, so Tests also extends each
 * others
 */
class SearchCarTypeTest extends AbstractCarTypeTest
{
	//Submitted data changes from the parent
	/**
	 * @dataProvider provider
	 */
	public function testSubmitValidData(array $formData)
	{
		$form = $this->factory->create(SearchCarType::class);

		$car = new Car($formData);

		$form->submit($formData);

		$this->assertTrue($form->isSynchronized());
		$this->assertEquals($car, $form->getData());

		//fields brand, model and description must have been removed
		$this->assertFalse($form->has('brand'));
		$this->assertFalse($form->has('model'));
		$this->assertFalse($form->has('description'));

		$view = $form->createView();
		$children = $view->children;

		foreach(array_keys($formData) as $key){
			$this->assertArrayHasKey($key, $children);
		}

		$errors = static::$validator->validate($form);
		$this->assertEmpty($errors);
	}

	public function provider()
	{
		return array(
			array(
				'fuel & sits' => array(
					'fuel' => 'diesel',
					'sits' => 5,
			)),
			array(
				'only fuel' => array(
					'fuel' => 'diesel',
			)),
			array(
				'only sits' => array(
					'sits' => 5,
			)),
			array('none' => array(
			)),
		);
	}
}
