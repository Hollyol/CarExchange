<?php

namespace App\Tests\Integration;

use App\Tests\AuthorizationTestCase;

class UserHomeIntegrationTest extends AuthorizationTestCase
{
	protected $client;

	public function setUp()
	{
		$this->client = $this->createAuthorizedUser();
	}

	/**
	 * @dataProvider localeProvider
	 */
	public function testUserHomeIntegration(string $locale)
	{
		$this->createAuthorizedUser();
		$crawler = $this->client->request('GET', $locale . 'users/');

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
		$contentNode = $crawler->filter('div.content');
		$this->assertCount(3, $contentNode->filter('p'));
		$this->assertCount(1, $contentNode->filter('fieldset'));
	}

	public function localeProvider()
	{
		return array(
			'francais' => ['/fr/'],
			'english' => ['/en/'],
		);
	}
}
