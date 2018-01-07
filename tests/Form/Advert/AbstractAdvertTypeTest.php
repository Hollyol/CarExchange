<?php

namespace App\Tests\Form\Advert;

use App\Form\Advert\AbstractAdvertType;
use App\Form\Car\AbstractCarType;
use App\Entity\Advert;
use App\Entity\Car;
use App\Entity\Location;
use App\Entity\Billing;
use App\Entity\Member;

use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AbstractAdvertTypeTest extends TypeTestCase
{
	private $validator;

	protected function getExtensions()
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

	//The form must be submitted correctly, map the values
	//and create relevant view
	/**
	 * @dataProvider provider
	 */
	public function testSubmitValidData(array $formData, Advert $defaultAdvert)
	{
		//test if the form compiles
		$form = $this->factory->create(AbstractAdvertType::class);

		$form->submit($formData);

		//test data transformers exceptions
		$this->assertTrue($form->isSynchronized());
		//test if the data is correctly setted
		$this->assertEquals($defaultAdvert, $form->getData());


		$view = $form->createView();
		$children = $view->children;

		foreach(array_keys($formData) as $key){
			$this->assertArrayHasKey($key, $children);
		}

		$this->assertEmpty($form->getData()->getRentals());
	}

	public function provider()
	{
		$car = new Car();
		$car->setBrand('Peugeot');
		$car->setModel('307');
		$car->setSits(5);
		$car->setFuel('diesel');
		$car->setDescription('Nice Car.');

		$location = new Location();
		$location->setCountry('FR');
		$location->setState('Alsace');
		$location->setTown('Strasbourg');

		$billing = new Billing();
		$billing->setPrice(10);
		$billing->setCurrency('Euro');
		$billing->setTimeBase('day');

		$advert = new Advert();
		$advert->setCar($car);
		$advert->setLocation($location);
		$advert->setBilling($billing);
		$advert->setBeginDate(new \Datetime('2018-01-01'));
		$advert->setEndDate(new \Datetime('2018-03-01'));
		$advert->setTitle('A Great Title');

		$tomorrow = time("tomorrow");

		return array(
			'everything provided' => array(
				[
					'car' => [
						'brand' => 'Peugeot',
						'model' => '307',
						'sits' => 5,
						'fuel' => 'diesel',
						'description' => 'Nice Car.',
					],
					'location' => [
						'country' => 'FR',
						'state' => 'Alsace',
						'town' => 'Strasbourg',
					],
					'billing' => [
						'price' => 10,
						'currency' => 'Euro',
						'timeBase' => 'day',
					],
					'beginDate' => [
						'year' => 2018,
						'month' => 01,
						'day' => 01,
					],
					'endDate' => [
						'year' => 2018,
						'month' => 03,
						'day' => 01,
					],
					'title' => 'A Great Title',
				],
			$advert,
			),
		);
	}
}
