<?php

namespace App\Tests\Form\Member;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;

use App\Form\Member\MemberSignUpType;
use App\Entity\Member;
use App\Entity\Location;

class MemberSignUpTypeTest extends KernelTestCase
{
	protected static $formFactory;
	protected static $validator;

	public static function setUpBeforeClass()
	{
		$container = static::bootKernel()->getContainer();
		self::$formFactory = $container
			->get('form.factory');
		self::$validator = Validation::createValidatorBuilder()
			->getValidator();
	}

	public function testSubmitValidData()
	{
		$location = new Location();
		$location->setCountry('FR');
		$location->setState('Alsace');
		$location->setTown('Strasbourg');

		$member = new Member();
		$member->setUsername('Hollyol');
		$member->setMail('hollyol@mail.com');
		$member->setPhone('09 09 09 09 09');
		$member->setLanguage('fr');
		$member->setLocation($location);

		$memberForm = new Member();

		$formData = array(
			'username' => 'Hollyol',
			'password' => [
				'first' => 'password',
				'second' => 'password',
			],
			'phone' => '09 09 09 09 09',
			'mail' => [
				'first' => 'hollyol@mail.com',
				'second' => 'hollyol@mail.com',
			],
			'language' => 'fr',
			'location' => [
				'country' => 'FR',
				'state' => 'Alsace',
				'town' => 'Strasbourg',
			],
		);

		$form = self::$formFactory->create(MemberSignUpType::class, $memberForm);
		$form->submit($formData);

		$this->assertTrue($form->isSynchronized());
		$this->assertNotNull($memberForm->getPassword());
		$memberForm->setPassword(null);
		$this->assertEquals($member, $form->getData());

		$errors = self::$validator->validate($form);
		$this->assertEmpty($errors);

		$children = $form->createView()->children;

		foreach (array_keys($formData) as $key) {
			$this->assertArrayHasKey($key, $children);
		}
	}

	/**
	 * If mail is not correctly confirmed, it will stay null. Such behaviour will
	 * trigger a type error in the MemberFormater::formatMail
	 *
	 * @expectedException \TypeError
	 */
	public function testFailedRepeatMail()
	{
		$formData = array(
			'username' => 'Hollyol',
			'password' => [
				'first' => 'password',
				'second' => 'password',
			],
			'phone' => '09 09 09 09 09',
			'mail' => [
				'first' => 'hollyol@mail.com',
				'second' => 'diferent@mail.com',
			],
			'language' => 'fr',
			'location' => [
				'country' => 'FR',
				'state' => 'Alsace',
				'town' => 'Strasbourg',
			],
		);

		$form = self::$formFactory->create(MemberSignUpType::class);
		$form->submit($formData);
	}
}
