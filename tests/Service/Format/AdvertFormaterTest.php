<?php

namespace App\Tests\Service\Format;

use App\Service\Format\AdvertFormater;
use App\Entity\Advert;

use PHPUnit\Framework\TestCase;

class AdvertFormaterTest extends TestCase
{
	/**
	 * @dataProvider advertProvider
	 */
	public function testFormatAdvert(Advert $advert)
	{
		$advertFormater = new AdvertFormater();
		$advertFormater->formatAdvert($advert);

		//A formated title must be like "Exemple Of A Great-title"
		$this->assertRegExp('/^[^a-z][^A-Z]*([ \t\f\v\n\r][^a-z][^A-Z]*)*$/', $advert->getTitle());
	}

	public function advertProvider()
	{
		$advert1 = new Advert();
		$advert1->setTitle('this is A/non. foRMated tITle');

		$advert2 = new Advert();
		$advert2->setTitle('a more current way to write');

		$advert3 = new Advert();
		$advert3->setTitle('4 aNY. assHOle/N3rd th4Th thinK-puting Numb3rs is C00l');

		return array(
			array($advert1),
			array($advert2),
			array($advert3),
		);
	}
}
