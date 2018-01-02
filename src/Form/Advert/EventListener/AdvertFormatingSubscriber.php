<?php

namespace App\Form\Advert\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use App\Service\Format\AdvertFormater;

class AdvertFormatingSubscriber implements EventSubscriberInterface
{
	private $formater;

	public function __construct()
	{
		$this->formater = new AdvertFormater();
	}

	public static function getSubscribedEvents()
	{
		return array(FormEvents::POST_SUBMIT => 'autoFormatAdvert');
	}

	public function autoFormatAdvert(FormEvent $event)
	{
		$this->formater->formatAdvert($event->getData());
	}
}
