<?php

namespace App\Tests\Navigation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutingTest extends WebTestCase
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

	public function testHomeRoute()
	{
		self::$client->request('GET', '/en/');
		$this->assertEquals(200, self::$client->getResponse()->getStatusCode());
		self::$client->request('GET', '/fr/');
		$this->assertEquals(200, self::$client->getResponse()->getStatusCode());
		self::$client->request('GET', '/en');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		self::$client->request('GET', '/fr');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
	}

	public function testSignupRoute()
	{
		self::$client->request('GET', '/en/users/signup/');
		$this->assertEquals(200, self::$client->getResponse()->getStatusCode());
		self::$client->request('GET', '/fr/users/signup/');
		$this->assertEquals(200, self::$client->getResponse()->getStatusCode());
		self::$client->request('GET', '/en/users/signup');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		self::$client->request('GET', '/en/users/signup');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
	}

	public function testLogoutRedirection()
	{
		self::$client->request('GET', '/en/logout');
		$this->assertEquals(302, self::$client->getResponse()->getStatusCode());
		$this->assertTrue(self::$client->getResponse()->isRedirect());
	}

	public function testLoginRoute()
	{
		self::$client->request('GET', '/en/login');
		$this->assertEquals(301, self::$client->getResponse()->getStatusCode());
		self::$client->request('GET', '/en/login/');
		$this->assertEquals(200, self::$client->getResponse()->getStatusCode());
	}
}
