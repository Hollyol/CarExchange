<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CoreLayerLinksTest extends WebTestCase
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

	public function testLoginLink()
	{
		$link = self::$client->request('GET', '/fr/')
			->selectLink('Connexion')
			->link();

		$crawler = self::$client->click($link);

		$this->assertEquals(200, self::$client->getResponse()->getStatusCode());
		$this->assertEquals('http://localhost/fr/login', $crawler->getUri());

		$link = self::$client->request('GET', '/en/')
			->selectLink('Sign In')
			->link();
		$crawler = self::$client->click($link);

		$this->assertEquals(200, self::$client->getResponse()->getStatusCode());
		$this->assertEquals('http://localhost/en/login', $crawler->getUri());
	}

	public function testSignUpLink()
	{
		$link = self::$client->request('GET', '/fr/')
			->selectLink('S\'enregistrer')
			->link();

		$crawler = self::$client->click($link);

		$this->assertEquals(200, self::$client->getResponse()->getStatusCode());
		$this->assertEquals('http://localhost/fr/users/signup/', $crawler->getUri());

		$link = self::$client->request('GET', '/en/')
			->selectLink('Sign Up')
			->link();
		$crawler = self::$client->click($link);

		$this->assertEquals(200, self::$client->getResponse()->getStatusCode());
		$this->assertEquals('http://localhost/en/users/signup/', $crawler->getUri());
	}

	public function testSearchCarLink()
	{
		$link = self::$client->request('GET', '/fr/')
			->selectLink('Cherchez une voiture')
			->link();

		$crawler = self::$client->click($link);

		$this->assertEquals(200, self::$client->getResponse()->getStatusCode());
		$this->assertEquals('http://localhost/fr/adverts/search/', $crawler->getUri());

		$link = self::$client->request('GET', '/en/')
			->selectLink('Look for a car')
			->link();
		$crawler = self::$client->click($link);

		$this->assertEquals(200, self::$client->getResponse()->getStatusCode());
		$this->assertEquals('http://localhost/en/adverts/search/', $crawler->getUri());
	}

	public function testHomeLink()
	{
		$link = self::$client->request('GET', '/fr/users/signup/')
			->selectLink('Accueil')
			->link();

		$crawler = self::$client->click($link);

		$this->assertEquals(200, self::$client->getResponse()->getStatusCode());
		$this->assertEquals('http://localhost/fr/', $crawler->getUri());

		$link = self::$client->request('GET', '/en/users/signup/')
			->selectLink('Home')
			->link();
		$crawler = self::$client->click($link);

		$this->assertEquals(200, self::$client->getResponse()->getStatusCode());
		$this->assertEquals('http://localhost/en/', $crawler->getUri());
	}
}
