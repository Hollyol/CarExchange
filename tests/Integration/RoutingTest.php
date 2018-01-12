<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutingTest extends WebTestCase
{
	public function testHomeRoute()
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/en/');

		$this->assertEquals(200, $client->getResponse()->getStatusCode(), var_dump($crawler->filter('title')));
	}
}
