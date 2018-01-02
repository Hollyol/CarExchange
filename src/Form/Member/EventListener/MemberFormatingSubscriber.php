<?php

namespace App\Form\Member\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use App\Service\Format\MemberFormater;

class MemberFormatingSubscriber implements EventSubscriberInterface
{
	private $formater;

	public function __construct()
	{
		$this->formater = new MemberFormater();
	}

	public static function getSubscribedEvents()
	{
		return array(FormEvents::SUBMIT => 'autoFormatMember');
	}

	public function autoFormatMember(FormEvent $event)
	{
		$this->formater->formatMember($event->getData());
	}
}
