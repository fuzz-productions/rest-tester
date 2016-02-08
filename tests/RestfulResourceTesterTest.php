<?php

namespace Fuzz\RestTests\Tests;

use Fuzz\RestTests\Resources\RestfulResource;
use Fuzz\RestTests\Tests\TestClasses\UserTestClass;

class RestfulResourceTesterTest extends BaseTestCase
{
	use RestfulResource;

	public function testItCanValidateModelImplementation()
	{
		$this->setExpectedException(\InvalidArgumentException::class, ' does not implement a model definition.');
		$this->validateImplementation();
	}

	public function testItCanValidateResourcePathImplementation()
	{
		$this->model = UserTestClass::class;

		$this->setExpectedException(\InvalidArgumentException::class, ' does not implement a resource path definition.');
		$this->validateImplementation();
	}

	public function testItReturnsModelClassString()
	{
		$this->model = UserTestClass::class;

		$this->assertEquals(UserTestClass::class, $this->model());
	}

	public function testItReturnsModelClassInstance()
	{
		$this->model = UserTestClass::class;

		$this->assertTrue($this->model(true) instanceof UserTestClass);
	}

	public function testItThrowsUsefulErrorOnModelNotImplemented()
	{
		$this->setExpectedException(\InvalidArgumentException::class, ' does not implement a model definition.');
		$this->model();
	}

	public function testItReturnsPath()
	{
		$this->baseUrl       = 'http://base.url.com';
		$this->api_version   = '1.2';
		$this->resource_path = 'users';

		$this->assertEquals('1.2/users', $this->path());
	}

	public function testItReturnsPathWithId()
	{
		$this->baseUrl       = 'http://base.url.com';
		$this->api_version   = '1.2';
		$this->resource_path = 'users';

		$this->assertEquals('1.2/users/1', $this->path(1));
	}

	public function testItThrowsUsefulErrorOnResourcePathNotImplemented()
	{
		$this->setExpectedException(\InvalidArgumentException::class, ' does not implement a resource path definition.');
		$this->path();
	}

	public function testItCanMakeGenericUpdateForAllTypes()
	{
		$this->model = UserTestClass::class;
		$user        = $this->model(true);

		// String
		$user->sample_field = 'Username';

		$this->makeGenericUpdate($user);

		$this->assertEquals('Username updated', $user->sample_field); // sample_field . ' updated'

		// Integer
		$user->sample_field = 1;

		$this->makeGenericUpdate($user);

		$this->assertEquals(2, $user->sample_field); // sample_field + 1

		// Boolean
		$user->sample_field = false;

		$this->makeGenericUpdate($user);

		$this->assertTrue($user->sample_field); // ! sample_field

		// NULL
		$user->sample_field = null;

		$this->makeGenericUpdate($user);

		$this->assertEquals(1, $user->sample_field); // null + 1
	}
}
