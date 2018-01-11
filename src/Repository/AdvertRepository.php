<?php

namespace App\Repository;

use App\Entity\Advert;
use Symfony\Component\HttpKernel\Exception\PreconditionRequiredHttpException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * AdvertRepository
 *
 */
class AdvertRepository extends \Doctrine\ORM\EntityRepository
{
	public function fetchSearchResults(Advert $advertType){
		$qb = $this->createQueryBuilder('a');
		
		$this->hasValidPeriod($qb, $advertType->getBeginDate(), $advertType->getEndDate());
		$this->whereCarLike($qb, $advertType->getCar());
		$this->whereCountryIs($qb, $advertType->getLocation()->getCountry());
		$this->whereStateIs($qb, $advertType->getLocation()->getState());
		$this->whereTownIs($qb, $advertType->getLocation()->getTown());

		return $qb->getQuery()->getResult();
	}

	public function hasValidPeriod(QueryBuilder $qb, \Datetime $beginDate, $endDate){
		if (!$beginDate OR !$endDate)
			throw new PreconditionRequiredHttpException('You must provide begin and end dates');

		$qb
			//adverts must be active during the provided period
			->where('a.beginDate < :beginDate')
			->andWhere('a.endDate > :endDate')
			//adverts MUST NOT have any rentals colliding the provided or not having rentals
			//cases of collision :
			//	beginDate during the rental period
			//	endDate during the rental period
			//	rental period included by the provided period
			->andWhere('NOT EXISTS (SELECT r.status FROM App\Entity\Rental r WHERE (
				(r.beginDate < :endDate AND :endDate < r.endDate)
				OR (r.beginDate < :beginDate AND :beginDate < r.endDate)
				OR (:beginDate < r.beginDate AND r.endDate < :endDate)))
				OR a.rentals IS EMPTY')

			->setParameter('beginDate', $beginDate->format('Y-m-d H:i:s'))
			->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
		;
	}

	public function whereCarLike(QueryBuilder $qb, $car){
		if ($car->getSits()){
			$qb
				->innerJoin('a.car', 'car', 'WITH',':sits <= car.sits')
				->setParameter('sits', $car->getSits());
		}
		if ($car->getFuel()){
			$qb
				->innerJoin('a.car', 'carf', 'WITH', ':fuel = carf.fuel')
				->setParameter('fuel', $car->getFuel());
		}
	}

	public function whereCountryIs(QueryBuilder $qb, $country){
		if (!$country)
			throw new PreconditionRequiredHttpException('You must provide the country');

		$qb
			->innerJoin('a.location', 'location', 'WITH', 'location.country = :country')
			->setParameter('country', $country);
	}

	public function whereStateIs(QueryBuilder $qb, $state){
		if ($state){
			$qb
				->andWhere('location.state = :state')
				->setParameter('state', $state);
		}
	}

	public function whereTownIs(QueryBuilder $qb, $town){
		if ($town){
			$qb
				->andWhere('location.town = :town')
				->setParameter('town', $town);
		}
	}
}
