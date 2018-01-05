<?php

namespace App\Form\Location\EventListener;

use App\Repository\LocationRepository;
use App\Entity\Location;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class LocationDuplicateSubscriber implements EventSubscriberInterface
{
	private $repos;

	public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
	{
		$this->repos = $em->getRepository(Location::class);
	}

	public static function getSubscribedEvents()
	{
		return array(
			FormEvents::SUBMIT => 'autoAvoidDuplicate'
		);
	}

	public function autoAvoidDuplicate(FormEvent $event)
	{
		$event->setData($this->repos->alreadyExists($event->getData()));
	}
}
