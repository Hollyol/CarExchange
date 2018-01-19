<?php

namespace App\Tests;

use App\Entity\Member;
use App\Entity\Location;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

abstract class AuthorizationTestCase extends WebTestCase
{
	protected static $em;
	protected static $session;

	public static function setUpBeforeClass()
	{
		$container = static::bootKernel()->getContainer();

		self::$session = $container
			->get('session');

		self::$em = $container
			->get('doctrine')
			->getManager();

		self::createUser();
	}

	public static function tearDownAfterClass()
	{
		$qb = self::$em->createQueryBuilder('m');
		$qb->delete()
			->from(Member::class, 'm');
		$qb->getQuery()->execute();

		$qb = self::$em->createQueryBuilder('l');
		$qb->delete()
			->from(Member::class, 'l');
		$qb->getQuery()->execute();

		parent::tearDownAfterClass();
	}

	protected function createAuthorizedUser()
	{
		$user = self::$em->getRepository(Member::class)
			->findOneByUsername('Hollyol');
		$token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());

		self::$session->set('_security_main',serialize($token));
		self::$session->save();

		$client = static::createClient();
		$client->getCookieJar()
			->set(new Cookie(self::$session->getName(), self::$session->getId()));

		return $client;
	}

	protected static function createUser()
	{
		$location = new Location();
		$location->setCountry('FR');
		$location->setState('Alsace');
		$location->setTown('town');

		$member = new Member();
		$member->setUsername('Hollyol');
		$member->setPassword('password');
		$member->setMail('hollyol@mail.com');
		$member->setPhone('09 09 09 09 09');
		$member->setLanguage('fr');
		$member->setLocation($location);

		self::$em->persist($location);
		self::$em->persist($member);
		self::$em->flush();
	}
}
