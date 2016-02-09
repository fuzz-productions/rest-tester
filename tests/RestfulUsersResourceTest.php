<?php

namespace Fuzz\RestTests\Tests;

use Fuzz\RestTests\Tests\Models\User;

class RestfulUsersResourceTest extends ApiResourceTestCase
{
	public function testItCanCreateUser()
	{
		$user = new User;
		$user->username = 'Username';
		$this->assertTrue($user->save());

		$this->assertNotNull(User::find(1));
	}
}
