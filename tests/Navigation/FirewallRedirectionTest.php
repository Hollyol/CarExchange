<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FirewallRedirectionTest extends WebTestCase
{
	protected static $client;

	public static function setUpBeforeClass()
	{
		self::$client = static::createClient();
	}

	public static function tearDownAfterClass()
	{
		parent::tearDownAfterClass();
	}

	public function testUserHome()
	{
		self::$client->request('GET', '/fr/users');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		self::$client->request('GET', '/en/users');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());
	}

	public function testAddAdvert()
	{
		self::$client->request('GET', '/fr/adverts/add');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		self::$client->request('GET', '/en/adverts/add');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());
	}

	public function testDeleteAdvert()
	{
		self::$client->request('GET', '/fr/adverts/delete/3');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		self::$client->request('GET', '/en/adverts/delete/3');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());
	}

	public function testEditAdvert()
	{
		self::$client->request('GET', '/fr/adverts/edit/3');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		self::$client->request('GET', '/en/adverts/edit/3');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());
	}

	public function testRentAdvert()
	{
		self::$client->request('GET', '/fr/adverts/rent/3');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());

		self::$client->request('GET', '/en/adverts/rent/3');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());
	}
}
