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
		$this->assertCount(2, $contentNode->filter('p'));
	}

	public function localeProvider()
	{
		return array(
			'francais' => ['/fr/'],
			'english' => ['/en/'],
		);
	}
}
