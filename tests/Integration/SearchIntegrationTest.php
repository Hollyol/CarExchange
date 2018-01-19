<?php

namespace App\Tests\Integration;

use App\Entity\Advert;
use App\Entity\Location;
use App\Entity\Car;
use App\Entity\Member;
use App\Entity\Billing;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchIntegrationTest extends WebTestCase
{
	protected static $client;

	public static function setUpBeforeClass()
	{
		self::$client = static::createClient();
	}

	/**
	 * @dataProvider localeProvider
	 */
	public function testNoSubmitIntegration($locale)
	{
		$crawler = self::$client->request('GET', '/' . $locale . '/adverts/search/');
		$this->assertCount(1, $crawler->filter('fieldset'));
		$this->assertCount(1, $crawler->filter('legend'));
		$formNode = $crawler->filter('form');
		$this->assertNotEmpty($formNode);
		$this->assertCount(4, $formNode->filter('input'));
		$this->assertCount(8, $formNode->filter('select'));
		$this->assertCount(9, $formNode->filter('label'));

		$translator = self::$client->getContainer()->get('translator');
		$labels = array(
			$translator->trans('Begin date', [], 'searchAdvert'),
			$translator->trans('End date', [], 'searchAdvert'),
			$translator->trans('Car', [], 'searchCar'),
			$translator->trans('Sits', [], 'searchCar'),
			$translator->trans('Fuel', [], 'searchCar'),
			$translator->trans('Location', [], 'searchLocation'),
			$translator->trans('Country', [], 'searchLocation'),
			$translator->trans('State', [], 'searchLocation'),
			$translator->trans('Town', [], 'searchLocation'),
		);

		foreach ($labels as $label) {
			$this->assertNotEmpty($formNode->filter('label:contains("' . $label . '")'));
		}

		$this->assertEmpty($crawler->filterXPath('//div[@id = "results"]'));
	}

	/**
	 * @dataProvider localeProvider
	 */
	public function testSubmitIntegration($locale)
	{
		$crawler = self::$client->request('POST', '/' . $locale . '/adverts/search/', array(
			'beginDate' => [
				'day' => 05,
				'month' => 01,
				'year' => 2018,
			],
			'endDate' => [
				'day' => 15,
				'month' => 01,
				'year' => 2018,
			],
			'location' => [
				'country' => 'FR',
			],
		));

		$resultsNode = $crawler->filterXPath('//div[@id = "results"]');
		$this->assertNotEmpty($resultsNode);
		$this->assertNotEmpty($resultsNode->filterXPath('//div[@id = "results_adverts"]'));
		$this->assertNotEmpty($resultsNode->filterXPath('//div[@id = "map_canva"]'));
	}

	public function localeProvider()
	{
		return array(
			'francais' => ['fr'],
			'english' => ['en'],
		);
	}
}
