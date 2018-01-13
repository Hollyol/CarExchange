<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SelectLanguageTest extends WebTestCase
{
	protected static $client;

	public static function setUpBeforeClass()
	{
		self::$client = static::createClient();
	}

	public function testChangeLanguage()
	{
		$crawler = self::$client->request('GET', '/fr/');

		$formSubmitNode = $crawler->selectButton('Submit');

		$form = $formSubmitNode->form(array(
			'language' => 'en',
		));

		self::$client->submit($form);

		$this->assertEquals(302, self::$client->getResponse()->getStatusCode());
	}
}
