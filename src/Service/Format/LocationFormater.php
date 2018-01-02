<?php

namespace App\Service\Format;

use App\Entity\Location;

class LocationFormater
{
	public function formatTown($town){
		return (ucwords(
			strtolower(preg_replace("/[^A-Za-z\d .'-]/", " ", $town)),
			" .'-")
		);
	}

	public function formatState($state){
		return (ucwords(
			strtolower(preg_replace("/[^A-Za-z\d .'-]/", " ", $state)),
			" .'-")
		);
	}

	public function formatLocation(Location $location){
		$location->setTown($this->formatTown($location->getTown()));
		$location->setState($this->formatState($location->getState()));
		//Country is not formated, since it is chosen in a fieldType
		return $location;
	}
}
