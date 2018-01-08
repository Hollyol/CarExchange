<?php

namespace App\Repository;

use App\Entity\Location;

/**
 * LocationRepository
 */
class LocationRepository extends \Doctrine\ORM\EntityRepository
{
	public function alreadyExists(Location $location)
	{
		$result = $this->findOneBy(array(
			'country' => $location->getCountry(),
			'state' => $location->getState(),
			'town' => $location->getTown(),
		));

		if (!$result)
			return $location;

		return $result;
	}
}
