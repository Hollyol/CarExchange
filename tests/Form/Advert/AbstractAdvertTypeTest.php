<?php

namespace App\Tests\Form\Advert;

use App\Form\Advert\AbstractAdvertType;
use App\Form\Car\AbstractCarType;
use App\Entity\Advert;
use App\Entity\Car;
use App\Entity\Location;
use App\Entity\Billing;
use App\Entity\Member;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AbstractAdvertTypeTest extends KernelTestCase
{
	use \App\Tests\Form\Advert\AdvertComponentCreationTrait;

	protected static $validator;
	protected static $formFactory;

	public static function setUpBeforeClass()
	{
		$container = self::bootKernel()
			->getContainer();

		self::$validator = $container
			->get('validator');

		self::$formFactory = $container
			->get('form.factory');
	}

	//The form must be submitted correctly, map the values
	//and create relevant view
	public function testSubmitValidData()
	{
		$advert = new Advert();
		$advert->setOwner($this->createValidOwner());

		$advertForm = clone $advert;

		$advert->setTitle('A Great Title');
		$advert->setBeginDate(new \Datetime('2020-01-01'));
		$advert->setEndDate(new \Datetime('2020-01-29'));
		$advert->setBilling($this->createValidBilling());
		$advert->setLocation($this->createValidLocation());
		$advert->setCar($this->createValidCar());

		$formData = array(
			'title' => 'A Great Title',
			'beginDate' => [
				'day' => 01,
				'month' => 01,
				'year' => 2020,
			],
			'endDate' => [
				'day' => 29,
				'month' => 01,
				'year' => 2020,
			],
			'location' => $this->createValidLocationArray(),
			'car' => $this->createValidCarArray(),
			'billing' => $this->createValidBillingArray(),
		);

		//test if the form compiles
		$form = self::$formFactory->create(AbstractAdvertType::class, $advertForm);

		$form->submit($formData);

		//test data transformers exceptions
		$this->assertTrue($form->isSynchronized());
		//test if the data is correctly setted
		$this->assertEquals($advert, $advertForm);

		$errors = self::$validator->validate($form);
		$this->assertEmpty($errors);


		$view = $form->createView();
		$children = $view->children;

		foreach(array_keys($formData) as $key){
			$this->assertArrayHasKey($key, $children);
		}
	}

	/**
	 * @dataProvider yearProvider
	 */
	public function testWrongDates(int $begYear, int $endYear, int $expected)
	{
		$advert = new Advert();
		$advert->setOwner($this->createValidOwner());

		$formData = array(
			'title' => 'A Great Title',
			'beginDate' => [
				'day' => 01,
				'month' => 01,
				'year' => $begYear,
			],
			'endDate' => [
				'day' => 29,
				'month' => 01,
				'year' => $endYear,
			],
			'location' => $this->createValidLocationArray(),
			'car' => $this->createValidCarArray(),
			'billing' => $this->createValidBillingArray(),
		);

		$form = self::$formFactory->create(AbstractAdvertType::class, $advert);
		$form->submit($formData);

		$errors = self::$validator->validate($form);
		$this->assertCount($expected, $errors);
	}

	public function yearProvider()
	{
		return array(
			'beginDate in the past' => [
				2016,
				2020,
				1,
			],
			'endDate in the past' => [
				2020,
				2016,
				2,
			],
		);
	}
}
