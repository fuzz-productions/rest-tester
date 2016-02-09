<?php

namespace Fuzz\RestTests\Tests;

use Fuzz\RestTests\Resources\RestfulResource;
use Fuzz\RestTests\Resources\TestsResourceCreate;
use Fuzz\RestTests\Resources\TestsResourceDelete;
use Fuzz\RestTests\Resources\TestsResourceIndex;
use Fuzz\RestTests\Resources\TestsResourceShow;
use Fuzz\RestTests\Resources\TestsResourceUpdate;
use Fuzz\RestTests\Tests\Models\User;

class RestfulUsersResourceTest extends ApiResourceTestCase
{
	// The tests..they test themselves
	use RestfulResource, TestsResourceIndex, TestsResourceCreate, TestsResourceShow, TestsResourceUpdate, TestsResourceDelete;

	public $model = User::class;

	public $resource_path = 'users';
}
