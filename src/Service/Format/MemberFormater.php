<?php

namespace App\Service\Format;

use Symfony\Component\Security\Core\User\UserInterface;

class MemberFormater
{
	public function formatMember(UserInterface $member)
	{
		if ($member->getPhone()) {
			$member->setPhone($this->formatPhone($member->getPhone()));
		}
		$member->setMail($this->formatMail($member->getMail()));

		return $member;
	}

	public function formatPhone(string $phone)
	{
		return preg_replace("/[^\d]/", " ", $phone);
	}

	public function formatMail(string $mail)
	{
		return strtolower($mail);
	}
}
