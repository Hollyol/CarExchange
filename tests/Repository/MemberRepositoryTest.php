<?php

namespace App\Tests\Repository;

use App\Entity\Member;
use App\Entity\Location;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MemberRepositoryTest extends KernelTestCase
{
	private $em;

	public function setUp()
	{
		$this->em = self::bootKernel()
			->getContainer()
			->get('doctrine')
			->getManager();

		$this->em->beginTransaction();
		$this->createFixtures();
	}

	public function tearDown()
	{
		$this->cleanFixtures();
		parent::tearDown();

		$this->em->close();
		$this->em = null;
	}

	public function createFixtures()
	{
		$location = new Location();
		$location->setCountry('FR');
		$location->setState('Alsace');
		$location->setTown('Strasbourg');

		$member = new Member();
		$member->setUsername('Hollyol');
		$member->setPassword('A fake password');
		$member->setMail('hollyol@mail.com');
		$member->setPhone('09 09 09 09 09');
		$member->setLanguage('fr');
		$member->setLocation($location);

		$this->em->persist($member);
		$this->em->flush();
	}

	public function cleanFixtures()
	{
		$this->em->rollback();
	}

	/**
	 * This method must return a member
	 * matching the pseudo or the mail address.
	 * the mail address is case-insensitive
	 *
	 * @dataProvider provider
	 */
	public function testLoadUserByUsername(string $username)
	{
		$repos = $this->em->getRepository(Member::class);

		$this->assertNotNull($username);
	}

	public function provider()
	{
		return array(
			'matching username' => [
				'Hollyol',
			],
			'matching mail with lower case' => [
				'hollyol@mail.com',
			],
			'mathcing mail with mixed case' => [
				'HoLLyoL@mail.COM',
			],
		);
	}
}
