<?php

namespace App\Tests\Form\Location;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\PreloadedExtension;

use Doctrine\ORM\EntityManager;

use App\Form\Location\AddLocationType;
use App\Entity\Location;

class AddLocationTypeTest extends KernelTestCase
{
	private $em;
	private $formFactory;

	public function setUp()
	{
		$container = self::bootKernel()
			->getContainer();

		$this->formFactory = $container
			->get('form.factory');

		$this->em = $container
			->get('doctrine')
			->getManager();
	}

	public function getExtensions()
	{
		$form = new AddLocationType($this->em);

		return array(
			new PreloadedExtension(array($form), array()),
		);
	}

	public function testSubmitValidData()
	{
		$formData = array(
			'country' => 'FR',
			'state' => 'Alsace',
			'town' => 'Strasbourg',
		);


		$location = new Location();
		$location->setCountry($formData['country']);
		$location->setState($formData['state']);
		$location->setTown($formData['town']);

		$form = $this->formFactory->createBuilder(AddLocationType::class)->getForm();

		$form->submit($formData);
		$this->assertTrue($form->isSynchronized());
		$this->assertEquals($location, $form->getData());

		$children = $form->createView()->children;

		foreach (array_keys($formData) as $key) {
			$this->assertArrayHasKey($key, $children);
		}
	}
}
