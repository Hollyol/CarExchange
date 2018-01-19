<?php

namespace App\Tests\Integration;

use App\Tests\AuthorizationTestCase;

class AddRentalIntegrationTest extends AuthorizationTestCase
{
	protected $client;

	public function setUp()
	{
		$this->client = $this->createAuthorizedUser();
	}

	/**
	 * @dataProvider uriProvider
	 */
	public function testAddAdvertIntegration(string $uri)
	{
		$crawler = $this->client->request('GET', $uri);

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
		$this->assertNotEmpty($crawler->filter('fieldset'));
		$formNode = $crawler->filter('form');
		$this->assertNotEmpty($formNode);
		$this->assertCount(8, $formNode->filter('input'));
		$this->assertCount(10, $formNode->filter('select'));
		$this->assertCount(1, $formNode->filter('button'));

		$this->assertCount(17, $formNode->filter('label'));
		$translator = $this->client->getContainer()->get('translator');
		$labels = array(
			$translator->trans('Title', [], 'addAdvert'),
			$translator->trans('Begin date', [], 'addAdvert'),
			$translator->trans('End date', [], 'addAdvert'),
			$translator->trans('Car', [], 'addCar'),
			$translator->trans('Brand', [], 'addCar'),
			$translator->trans('Model', [], 'addCar'),
			$translator->trans('Sits', [], 'addCar'),
			$translator->trans('Fuel', [], 'addCar'),
			$translator->trans('Description', [], 'addCar'),
			$translator->trans('Location', [], 'addLocation'),
			$translator->trans('Country', [], 'addLocation'),
			$translator->trans('State', [], 'addLocation'),
			$translator->trans('Town', [], 'addLocation'),
			$translator->trans('Billing', [], 'addBilling'),
			$translator->trans('Currency', [], 'addBilling'),
			$translator->trans('Price', [], 'addBilling'),
			$translator->trans('Time base', [], 'addBilling'),
		);

		foreach ($labels as $label) {
			$this->assertCount(1, $formNode->filter('label:contains("' . $label . '")'));
		}
	}

	public function uriProvider()
	{
		return array(
			'francais' => ['/fr/adverts/add/'],
			'english' => ['/en/adverts/add/'],
		);
	}
}
