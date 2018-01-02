<?php

namespace App\Tests\Service\Format;

use App\Entity\Member;
use App\Service\Format\MemberFormater;
use Symfony\Component\Security\Core\User\UserInterface;

use PHPUnit\Framework\TestCase;

class MemberFormaterTest extends TestCase
{
	/**
	 * @dataProvider memberProvider
	 */
	public function testMemberFormater(Member $member)
	{
		$formater = new MemberFormater();
		$formater->formatMember($member);

		$this->assertRegExp('/^\d{2}( \d{2}){4}$/', $member->getPhone());
		$this->assertRegExp('/[^A-Z]/', $member->getMail());
	}

	public function memberProvider()
	{
		$mem1 = new Member();
		$mem1->setPhone('09,09/09_09#90');
		$mem1->setMail('aL3XdU46@Vlamail.com');

		return array(
			'Numbers (mail), special characters (phone)' => array($mem1),
		);
	}
}
