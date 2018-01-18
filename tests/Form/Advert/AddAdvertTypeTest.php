<?php

namespace App\Tests\Form\Advert;

use App\Form\Advert\AddAdvertType;
use App\Entity\Advert;
use App\Tests\Form\Advert\AdvertComponentCreationTrait;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AddAdvertTypeTest extends KernelTestCase
{
	use AdvertComponentCreationTrait;

	protected static $em;
	protected static $validator;
	protected static $container;

	public static function setUpBeforeClass()
	{
		self::$container = static::bootKernel()
			->getContainer();

		self::$em = self::$container
			->get('doctrine')
			->getManager();

		self::$validator = Validation::createValidatorBuilder()
			->enableAnnotationMapping()
			->getValidator();
	}

	public function getExtensions()
	{
		$form = new AddLocationType(self::$em);

		return array(
			new ValidatorExtension(self::$validator),
			new PreloadedExtension(array($form), array()),
		);
	}

	public function testSubmitValidData()
	{
		$owner = $this->createValidOwner();
		$advert = new Advert();
		$advert->setOwner($owner);

		$advert_form = clone $advert;

		$form = self::$container->get('form.factory')
			->create(AddAdvertType::class, $advert_form);

		$advert->setTitle('A Great Title');
		$advert->setBeginDate(new \Datetime('2018-01-01'));
		$advert->setEndDate(new \Datetime('2018-01-29'));
		$advert->setLocation($this->createValidLocation());
		$advert->setBilling($this->createValidBilling());
		$advert->setCar($this->createValidCar());

		$formData = array(
			'title' => 'A great title',
			'beginDate' => [
				'year' => 2018,
				'month' => 1,
				'day' => 1,
			],
			'endDate' => [
				'year' => 2018,
				'month' => 1,
				'day' => 29,
			],
			'location' => $this->createValidLocationArray(),
			'billing' => $this->createValidBillingArray(),
			'car' => $this->createValidCarArray(),
		);

		$form->submit($formData);
		$this->assertTrue($form->isSynchronized());
		$this->assertEquals($advert, $advert_form);

		$errors = self::$validator->validate($form);
		$this->assertEmpty($errors);

		$children = $form->createView()->children;

		foreach (array_keys($formData) as $key) {
			$this->assertArrayHasKey($key, $children);
		}
	}

}
