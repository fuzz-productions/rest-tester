<?php

namespace Fuzz\RestTests\Tests;

use Fuzz\RestTests\Resources\RestfulResource;
use Fuzz\RestTests\Resources\TestsResourceIndex;
use Fuzz\RestTests\Tests\Models\User;

class RestfulUsersResourceTest extends ApiResourceTestCase
{
	use RestfulResource, TestsResourceIndex;

	public $model = User::class;

	public $resource_path = 'users';
}
