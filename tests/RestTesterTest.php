<?php

namespace Fuzz\RestTests\Tests;

use Fuzz\RestTests\Tests\TestClasses\OauthClientTestClass;
use Fuzz\RestTests\Tests\TestClasses\UserTestClass;

class RestTesterTest extends BaseTestCase
{
	public function testItReturnsApiVersion()
	{
		$this->api_version = '1.22';

		$this->assertEquals('1.22', $this->apiVersion());
	}

	public function testItThrowsUsefulErrorOnApiVersionNotImplementedInApiVersionMethod()
	{
		$this->setExpectedException(\InvalidArgumentException::class, self::class . ' does not implement an API version definition.');
		$this->apiVersion();
	}

	public function testItReturnsRelativeUrl()
	{
		$this->baseUrl     = 'http://base.url.com/';
		$this->api_version = '1.2';
		$this->assertEquals('1.2/an-example-path', $this->url('an-example-path'));
	}

	public function testItThrowsUsefulErrorOnBaseUrlNotImplementedInUrlMethod()
	{
		$this->setExpectedException(\InvalidArgumentException::class, self::class . ' does not implement a base url definition.');
		$this->url('an-example-path');
	}

	public function testItThrowsUsefulErrorOnApiVersionNotImplementedInUrlMethod()
	{
		$this->baseUrl = 'http://base.url.com';
		$this->setExpectedException(\InvalidArgumentException::class, self::class . ' does not implement an API version definition.');
		$this->url('an-example-path');
	}

	public function testItReturnsUserTestClassClassString()
	{
		$this->user_class = UserTestClass::class;

		$this->assertEquals(UserTestClass::class, $this->userClass());
	}

	public function testItReturnsUserTestClassClassInstance()
	{
		$this->user_class = UserTestClass::class;

		$this->assertTrue($this->userClass(true) instanceof UserTestClass);
	}

	public function testItThrowsUsefulErrorOnUserTestClassClassNotImplemented()
	{
		$this->setExpectedException(\InvalidArgumentException::class, self::class . ' does not implement a User class definition.');
		$this->userClass();
	}
}
