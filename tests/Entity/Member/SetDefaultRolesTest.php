<?php

namespace App\Tests\Entity\Member;

use PHPUnit\Framework\TestCase;

use App\Entity\Member;

class SetDefaultRolesTest extends TestCase
{
	/**
	 * If the member has a role already, setDefaultRoles do nothing
	 */
	public function testIgnore()
	{
		$roles = ['ROLE_SUBADMIN'];
		$member = new Member();
		$member->setRoles(['ROLE_ADMIN']);

		$member2 = clone $member;
		$member2->setDefaultRoles($roles);

		$this->assertSame($member->getRoles(), $member2->getRoles());
	}

	/**
	 * If the member doesn't have any roles, setDefaultRoles sets one
	 */
	public function testSetRole()
	{
		$member = new Member();
		$member->setDefaultRoles();

		$this->assertSame($member->getRoles(), ['ROLE_USER']);
	}
}
