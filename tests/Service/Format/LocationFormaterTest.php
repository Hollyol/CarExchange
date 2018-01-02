<?php

namespace App\Tests\Service\Format;

use App\Entity\Location;
use App\Service\Format\LocationFormater;
use PHPUnit\Framework\TestCase;

class LocationFormaterTest extends TestCase
{
	/**
	 * @dataProvider locationProvider
	 */
	public function testLocationFormater(Location $location)
	{
		$locationFormater = new LocationFormater();

		$locationFormater->formatLocation($location);

		//Town (and states) names must be formated as : "Chavannes Sur-L'Etang"
		$this->assertRegExp('/^[\dA-Z][a-z\d]*([ .\'-]+[\dA-Z][a-z\d]*)*$/', $location->getTown());
		$this->assertRegExp('/^[\dA-Z][a-z\d]*([ .\'-]+[\dA-Z][a-z\d]*)*$/', $location->getState());
	}

	public function locationProvider()
	{
		$loc1 = new Location();
		$loc1->setTown('strasbourg');
		$loc1->setState('alSace');

		$loc2 = new Location();
		$loc2->setTown('chavAnnes sous-l\'etang');
		$loc2->setState('tErritoire-de-Belfort');

		$loc3 = new Location();
		$loc3->setTown('aN_h0rribL3-Way.tO?nAMe#aTowN');
		$loc3->setState('7he°sAmebut/WiTH_aStaTE namE');

		return array(
			'Simple Name' => array($loc1),
			'Space, quote, hyphen' => array($loc2),
			'Digits, Special characters, underscore' => array($loc3),
		);
	}
}
