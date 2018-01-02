<?php

namespace App\Form\Location\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Service\Format\LocationFormater;

class LocationFormatingSubscriber implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(FormEvents::POST_SUBMIT => 'autoFormatLocation');
	}

	public function autoFormatLocation(FormEvent $event)
	{
		$formater = new LocationFormater();
		$formater->formatLocation($event->getData());
	}
}
