<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LayoutIntegrationTest extends WebTestCase
{
	protected static $client;

	public static function setUpBeforeClass()
	{
		self::$client = static::createClient();
	}

	/**
	 * @dataProvider uriProvider
	 */
	public function testHeaderIntegration(string $uri)
	{
		$crawler = self::$client->request('GET', $uri);
		$headerNode = $crawler->filterXPath('//header[@id="main_header"]');

		$this->assertNotEmpty($headerNode);
		//The header must contain one 'h1' title
		$this->assertNotEmpty($headerNode->filter('h1'));
		//The header must contain one nav with class 'main_links'
		$this->assertCount(1, $headerNode->filter('nav.main_links'));
		//The nav with class 'main_links' must contain 6 links
		$this->assertCount(6, $headerNode->filter('nav.main_links')->children());
		//The header must contain one nav with class 'meta_links'
		$this->assertCount(1, $headerNode->filter('nav.meta_links'));

		$formNode = $headerNode->filterXPath('//form[@id="language_form"]');
		$this->assertNotEmpty($formNode);
		//The form must have one choice field
		$this->assertCount(1, $formNode->filterXPath('//select[@name="language"]'));
		//The choice field must have 2 options
		$this->assertCount(2, $formNode->filterXPath('//select[@name="language"]')->children());
		//The form must have one hidden field named url
		$this->assertCount(1, $formNode->filterXPath('//input[@name="url"]')->filterXPath('//input[@type="hidden"]'));
		//The form must have one field for submition
		$this->assertCount(1, $formNode->filterXPath('//input[@type="submit"]'));
	}

	/**
	 * @dataProvider uriProvider
	 */
	public function testFooterIntegration(string $uri)
	{
		$crawler = self::$client->request('GET', $uri);
		$footerNode = $crawler->filterXPath('//footer[@id="main_footer"]');

		$this->assertNotEmpty($footerNode);
		$this->assertNotEmpty($footerNode->filterXPath('//div[@id="generic_footing"]'));
		$this->assertCount(2, $footerNode->filterXPath('//div[@id="generic_footing"]')->children());
	}

	public function uriProvider()
	{
		return array(
			'french' => ['/fr/'],
			'english' => ['/en/'],
		);
	}
}
