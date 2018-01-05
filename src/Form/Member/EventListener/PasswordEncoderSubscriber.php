<?php

namespace App\Form\Member\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Member;

class PasswordEncoderSubscriber implements EventSubscriberInterface
{
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
	}

	public static function getSubscribedEvents()
	{
		return array(
			FormEvents::POST_SUBMIT => 'autoEncodeUserPassword'
		);
	}

	public function autoEncodeUserPassword(FormEvent $event)
	{
		$event->getData()->setPassword(
			$this->encoder->encodePassword($event->getData(), $event->getData()->getPassword())
		);
	}
}
