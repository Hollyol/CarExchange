<?php

namespace App\Service\Format;

use App\Entity\Advert;

class AdvertFormater
{
	//Here we format only raw data, not objects or entities, they may have
	//their own formater
	public function formatTitle($title)
	{
		return (ucwords(strtolower($title)));
	}

	public function formatAdvert(Advert $advert){
		$advert->setTitle($this->formatTitle($advert->getTitle()));

		return $advert;
	}
}
