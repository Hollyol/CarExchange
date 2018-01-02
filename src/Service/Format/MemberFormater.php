<?php

namespace App\Service\Format;

use App\Entity\Member;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MemberFormater
{
	public function formatMember(Member $member)
	{
		$member->setPhone($this->formatPhone($member->getPhone()));
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
