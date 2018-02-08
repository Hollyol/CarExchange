<?php

namespace App\Tests\Validation;

use App\Entity\Member;
use App\Entity\Location;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MemberValidationTest extends KernelTestCase
{
	protected static $validator;
	protected static $em;

	public static function setUpBeforeClass()
	{
		$container = static::bootKernel()
			->getContainer();

		self::$validator = $container
			->get('validator');

		self::$em = $container
			->get('doctrine')
			->getManager();

		self::createFixtures();
	}

	public static function tearDownAfterClass()
	{
		$cleaner = self::$em->createQueryBuilder('m');
		$cleaner->delete()
			->from(Member::class, 'm');
		$cleaner->getQuery()->execute();

		$cleaner = self::$em->createQueryBuilder('l');
		$cleaner->delete()
			->from(Location::class, 'l');
		$cleaner->getQuery()->execute();
		parent::tearDownAfterClass();
	}

	/**
	 * @dataProvider validProvider
	 */
	public function testValidMember(Location $location, array $memberData)
	{
		$member = new Member();
		$member->setUsername($memberData['username']);
		$member->setPassword($memberData['password']);
		$member->setPhone($memberData['phone']);
		$member->setMail($memberData['mail']);
		$member->setLanguage($memberData['language']);
		$member->setLocation($location);

		$errors = self::$validator->validate($member);

		$this->assertEmpty($errors);
	}

	/**
	 * @dataProvider singleErrorProvider
	 */
	public function testSingleError(array $locationData, array $memberData, string $expectedOrigin)
	{
		$location = new Location();
		$location->setTown($locationData['town']);
		$location->setState($locationData['state']);
		$location->setCountry($locationData['country']);

		$member = new Member();
		$member->setUsername($memberData['username']);
		$member->setPassword($memberData['password']);
		$member->setPhone($memberData['phone']);
		$member->setMail($memberData['mail']);
		$member->setLanguage($memberData['language']);
		$member->setLocation($location);

		$errors = self::$validator->validate($member);

		$this->assertCount(1, $errors);
		$this->assertEquals($expectedOrigin, $errors[0]->getPropertyPath());
	}

	public function testLocationConstraint()
	{
		$location = new Location();

		$member = new Member();
		$member->setUsername('Hollyol');
		$member->setPassword('fake');
		$member->setPhone('09 09 09 09 09');
		$member->setMail('hollyol@mail.com');
		$member->setLanguage('fr');

		//First we test the NotBlank constraint
		$errors = self::$validator->validate($member);

		$this->assertCount(1, $errors);
		$this->assertEquals('location', $errors[0]->getPropertyPath());

		//Then the Valid constraint
		$member->setLocation($location);

		$errors = self::$validator->validate($member);
		$this->assertNotEmpty($errors);
	}

	public function testUniqueEntities()
	{
		$location = new Location();
		$location->setCountry('FR');
		$location->setState('Alsace');
		$location->setTown('Strasbourg');

		$member = new Member();
		$member->setUsername('Woliro');
		$member->setPassword('fake');
		$member->setPhone('09 09 09 09 09');
		$member->setMail('hollyol@mail.com');
		$member->setLanguage('fr');
		$member->setLocation($location);

		$errors = self::$validator->validate($member);

		$this->assertCount(1, $errors);
		$this->assertEquals('username', $errors[0]->getPropertyPath());

		$member->setUsername('Hollyol');
		$member->setMail('woliro@mail.com');

		$errors = self::$validator->validate($member);

		$this->assertCount(1, $errors);
		$this->assertEquals('mail', $errors[0]->getPropertyPath());

		$member->setMail('hollyol@mail.com');
		$member->setPhone('08 08 08 08 08');

		$errors = self::$validator->validate($member);

		$this->assertCount(1, $errors);
		$this->assertEquals('phone', $errors[0]->getPropertyPath());
	}

	public function singleErrorProvider()
	{
		$validLocation = [
			'country' => 'FR',
			'state' => 'Alsace',
			'town' => 'Strasbourg',
		];

		return array(
			'language not valid' => array(
				$validLocation,
				[
					'username' => 'Hollyol',
					'password' => 'fake',
					'phone' => '09 09 09 09 09',
					'mail' => 'hollyol@mail.com',
					'language' => 'english',
				],
				'language',
			),
			'language not provided' => array(
				$validLocation,
				[
					'username' => 'Hollyol',
					'password' => 'fake',
					'phone' => '09 09 09 09 09',
					'mail' => 'hollyol@mail.com',
					'language' => '',
				],
				'language',
			),
			'username too long' => array(
				$validLocation,
				[
					'username' => 'This username is too long and should trigger a validation error',
					'password' => 'fake',
					'phone' => '09 09 09 09 09',
					'mail' => 'hollyol@mail.com',
					'language' => 'fr',
				],
				'username',
			),
			'username not provided' => array(
				$validLocation,
				[
					'username' => '',
					'password' => 'fake',
					'phone' => '09 09 09 09 09',
					'mail' => 'hollyol@mail.com',
					'language' => 'fr',
				],
				'username',
			),
			'phone number too long' => array(
				$validLocation,
				[
					'username' => 'Hollyol',
					'password' => 'fake',
					'phone' => '09 09 09 09 09 09 09 09 09 09 09 09 09 0 909 09 09 09 09 09 09 09',
					'mail' => 'hollyol@mail.com',
					'language' => 'fr',
				],
				'phone',
			),
			'mail not valid' => array(
				$validLocation,
				[
					'username' => 'Hollyol',
					'password' => 'fake',
					'phone' => '09 09 09 09 09',
					'mail' => 'hollyolcom',
					'language' => 'fr',
				],
				'mail',
			),
			'mail too long' => array(
				$validLocation,
				[
					'username' => 'Hollyol',
					'password' => 'fake',
					'phone' => '',
					'mail' => 'hollyolthismailhasavalidformatbutisfartoolongsoitshouldtriggeranerrorwhilebeiingvalidated.otherwiseiwouldbeembarrassed@mail.com',
					'language' => 'fr',
				],
				'mail',
			),
			'mail not provided' => array(
				$validLocation,
				[
					'username' => 'Hollyol',
					'password' => 'fake',
					'phone' => '',
					'mail' => '',
					'language' => 'fr',
				],
				'mail',
			),
		);
	}

	public function validProvider()
	{
		$validLocation = new Location();
		$validLocation->setCountry('FR');
		$validLocation->setState('Alsace');
		$validLocation->setTown('Strasbourg');

		return array(
			'everything provided' => array(
				$validLocation,
				[
					'username' => 'Hollyol',
					'password' => 'password',
					'phone' => '09 09 09 09 09',
					'mail' => 'hollyol@mail.com',
					'language' => 'fr',
				],
			),
			'phone not provided' => array(
				$validLocation,
				[
					'username' => 'Hollyol',
					'password' => 'password',
					'phone' => '',
					'mail' => 'hollyol@mail.com',
					'language' => 'fr',
				],
			),
			'password not provided' => array(
				$validLocation,
				[
					'username' => 'Hollyol',
					'password' => '',
					'phone' => '09 09 09 09 09',
					'mail' => 'hollyol@mail.com',
					'language' => 'fr',
				],
			),
		);
	}

	public static function createFixtures()
	{
		$location = new Location();
		$location->setTown('Strasbourg');
		$location->setState('Alsace');
		$location->setCountry('FR');

		$member = new Member();
		$member->setUsername('Woliro');
		$member->setPassword('fake');
		$member->setPhone('08 08 08 08 08');
		$member->setMail('woliro@mail.com');
		$member->setLanguage('fr');
		$member->setLocation($location);

		self::$em->persist($location);
		self::$em->persist($member);
		self::$em->flush();
	}
}
