<?php

namespace App\Tests\Form\Member;

use Symfony\Component\Form\Test\TypeTestCase;

use App\Form\Member\AbstractMemberType;
use App\Entity\Member;
use App\Entity\Location;

use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AbstractMemberTypeTest extends TypeTestCase
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

	public function testSubmitData()
	{
		$formData = array(
			'username' => 'Sebi',
			'password' => 'password',
			'mail' => 'sebi@mail.com',
			'phone' => '09 09 09 09 09',
			'location' => [
				'country' => 'FR',
				'state' => 'Alsace',
				'town' => 'Mulhouse',
			],
			'language' => 'fr',
		);

		$form = $this->factory->create(AbstractMemberType::class);
		$memberLocation = new Location();
		$memberLocation->setCountry($formData['location']['country']);
		$memberLocation->setState($formData['location']['state']);
		$memberLocation->setTown($formData['location']['town']);

		$member = new Member();
		$member->setUsername($formData['username']);
		$member->setPassword($formData['password']);
		$member->setMail($formData['mail']);
		$member->setPhone($formData['phone']);
		$member->setLocation($memberLocation);
		$member->setLanguage($formData['language']);

		$form->submit($formData);

		$this->assertTrue($form->isSynchronized());
		$this->assertEquals($member, $form->getData());

		$children = $form->createView()->children;

		foreach (array_keys($formData) as $key) {
			$this->assertArrayHasKey($key, $children);
		}
	}
}
