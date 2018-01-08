<?php

namespace App\Tests\Repository;

use App\Entity\Location;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LocationRepositoryTest extends KernelTestCase
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * {@inheritDoc}
	 */
	protected function setUp()
	{
		$kernel = self::bootKernel();

		$this->em = $kernel->getContainer()
			->get('doctrine')
			->getManager();
	}

	/*
	 * If the location is already in the database, alreadyExists will return
	 * the existing location
	 */
	public function testAlreadyExists()
	{
		$location = new Location();
		$location->setCountry('FR');
		$location->setState('Alsace');
		$location->setTown('Strasbourg');
		$result = $this->em
			->getRepository(Location::class)
			->alreadyExists($location);

		$this->assertNotSame($location, $result);
		$this->assertSame($location->getCountry(), $result->getCountry());
		$this->assertSame($location->getState(), $result->getState());
		$this->assertSame($location->getTown(), $result->getTown());
	}

	/*
	 * If the location is not already in the database, alreadyExists will return
	 * the location used as argument
	 */
	public function testNotAlreadyExists()
	{
		$location = new Location();
		$location->setCountry('Fake');
		$location->setState('fake');
		$location->setTown('fakeTown');

		$result = $this->em
			->getRepository(Location::class)
			->alreadyExists($location);

		$this->assertSame($location, $result);
	}
}
