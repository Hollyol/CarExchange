<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\XliffFileLoader;


class SignUpIntegrationTest extends WebTestCase
{
	protected static $client;

	public static function setUpBeforeClass()
	{
		self::$client = static::createClient();
	}

	/**
	 * @dataProvider localeProvider
	 */
	public function testSignUpFormIntegration(string $locale)
	{
		$crawler = self::$client->request('GET', '/' . $locale . '/users/signup/');

		$this->assertCount(1, $crawler->filter('fieldset'));
		$this->assertCount(1, $crawler->filter('legend'));
		$formNode = $crawler->filter('form');
		$this->assertNotEmpty($formNode);
		$this->assertCount(9, $formNode->filter('input'));
		$this->assertCount(2, $formNode->filter('select'));
		$this->assertCount(11, $formNode->filter('label'));

		$translator = self::$client->getContainer()->get('translator');
		$labels = array(
			$translator->trans('Username', [], 'signup'),
			$translator->trans('Password', [], 'signup'),
			$translator->trans('Confirm Password', [], 'signup'),
			$translator->trans('E-mail', [], 'signup'),
			$translator->trans('Confirm E-mail', [], 'signup'),
			$translator->trans('Phone', [], 'signup'),
			$translator->trans('Location', [], 'signup'),
			$translator->trans('Country', [], 'signup'),
			$translator->trans('State', [], 'signup'),
			$translator->trans('Town', [], 'signup'),
			$translator->trans('Language', [], 'signup'),
		);

		foreach ($labels as $label) {
			$this->assertNotEmpty($formNode->filter('label:contains("' . $label . '")'));
		}
	}

	public function localeProvider()
	{
		return array(
			'francais' => ['fr'],
			'english' => ['en'],
		);
	}
}
