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
		$this->hasValidRentalPeriods($qb, $advertType->getBeginDate(), $advertType->getEndDate());
		$this->whereCarLike($qb, $advertType->getCar());
		$this->whereCountryIs($qb, $advertType->getLocation()->getCountry());
		$this->whereStateIs($qb, $advertType->getLocation()->getState());
		$this->whereTownIs($qb, $advertType->getLocation()->getTown());

		return $qb->getQuery()->getResult();
	}

	public function hasValidPeriod(QueryBuilder $qb, $beginDate, $endDate){
		if (!$beginDate OR !$endDate)
			throw new PreconditionRequiredHttpException('You must provide begin and end dates');

		$qb
			->andWhere('a.beginDate < :beginDate')
			->andWhere('a.endDate > :endDate')
			->setParameter('beginDate', $beginDate)
			->setParameter('endDate', $endDate)
		;
	}

	public function hasValidRentalPeriods(QueryBuilder $qb, $beginDate, $endDate){
		if (!$beginDate OR !$endDate)
			throw new PreconditionRequiredHttpException('You must provide begin and end dates');
		$qb
			->leftJoin('a.rentals', 'rental', 'WITH NOT',
				'((:beginDate BETWEEN rental.beginDate AND rental.endDate)
				OR (:endDate BETWEEN rental.beginDate AND rental.endDate))')
			->setParameter('beginDate', $beginDate)
			->setParameter('endDate', $endDate)
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
		if ($country){
			$qb
				->innerJoin('a.location', 'location', 'WITH', 'location.country = :country')
				->setParameter('country', $country);
		}
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
