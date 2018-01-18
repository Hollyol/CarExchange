<?php

namespace App\Tests\Navigation;

use App\Entity\Member;
use App\Tests\AuthorizationTestCase;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FirewallRedirectionTest extends AuthorizationTestCase
{
	protected static $client;
	protected static $token;

	public static function setUpBeforeClass()
	{
		self::$client = static::createClient();
		parent::setUpBeforeClass();
	}

	public static function tearDownAfterClass()
	{
		parent::tearDownAfterClass();
	}

	/**
	 * @dataProvider localeProvider
	 */
	public function testUserHome(string $locale)
	{
		self::$client->request('GET', $locale . 'users');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		self::$client->request('GET', $locale . 'users/');
		$this->assertEquals(302, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		$authClient = $this->createAuthorizedUser();
		$authClient->request('GET', $locale . 'users/');
		$this->assertEquals(200, $authClient->getResponse()->getStatusCode());
		$this->assertFalse($authClient->getResponse()->isRedirect());
	}

	/**
	 * @dataProvider localeProvider
	 */
	public function testAddAdvert(string$locale)
	{
		self::$client->request('GET', $locale . 'adverts/add');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		self::$client->request('GET', $locale . 'adverts/add/');
		$this->assertEquals(302, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		$authClient = $this->createAuthorizedUser();
		$authClient->request('GET', $locale . 'adverts/add/');
		$this->assertEquals(200, $authClient->getResponse()->getStatusCode());
		$this->assertFalse($authClient->getResponse()->isRedirect());
	}

	/**
	 * @dataProvider localeProvider
	 */
	public function testDeleteAdvert(string $locale)
	{
		self::$client->request('GET', $locale . 'adverts/delete/3');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		self::$client->request('GET', $locale . 'adverts/delete/3/');
		$this->assertEquals(302, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		//Since the advert doesn't exists, this route will lead to a 404 error
		$authClient = $this->createAuthorizedUser();
		$authClient->request('GET', $locale . 'adverts/delete/3/');
		$this->assertEquals(404, $authClient->getResponse()->getStatusCode());
		$this->assertFalse($authClient->getResponse()->isRedirect());
	}

	/**
	 * @dataProvider localeProvider
	 */
	public function testEditAdvert(string $locale)
	{
		self::$client->request('GET', $locale . 'adverts/edit/3');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		self::$client->request('GET', $locale . 'adverts/edit/3/');
		$this->assertEquals(302, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		$authClient = $this->createAuthorizedUser();
		$authClient->request('GET', $locale . 'adverts/edit/3/');
		$this->assertEquals(404, $authClient->getResponse()->getStatusCode());
		$this->assertFalse($authClient->getResponse()->isRedirect());
	}

	/**
	 * @dataProvider localeProvider
	 */
	public function testRentAdvert(string $locale)
	{
		self::$client->request('GET', $locale . 'adverts/rent/3');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		self::$client->request('GET', $locale . 'adverts/rent/3/');
		$this->assertEquals(302, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		$authClient = $this->createAuthorizedUser();
		$authClient->request('GET', $locale . 'adverts/rent/3/');
		$this->assertEquals(404, $authClient->getResponse()->getStatusCode());
		$this->assertFalse($authClient->getResponse()->isRedirect());
	}

	public function localeProvider()
	{
		return array(
			'francais' => ['/fr/'],
			'english' => ['/en/'],
		);
	}
}
