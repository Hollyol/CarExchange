<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SignInIntegrationTest extends WebTestCase
{
	protected static $client;

	public static function setUpBeforeClass()
	{
		self::$client = static::createClient();
	}

	/**
	 * @dataProvider localeProvider
	 */
	public function testSignInIntegration($locale)
	{
		$crawler = self::$client->request('GET', '/' . $locale . '/login');

		$this->assertNotEmpty($crawler->filter('fieldset'));
		$this->assertNotEmpty($crawler->filter('legend'));
		$formNode = $crawler->filter('form');
		$this->assertNotEmpty($formNode);
		$this->assertCount(2, $formNode->filter('label'));
		$this->assertCount(1, $formNode->filter('button'));
	}

	public function localeProvider()
	{
		return array(
			'francais' => ['fr'],
			'english' => ['en'],
		);
	}
}
