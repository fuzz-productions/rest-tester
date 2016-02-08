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
		$this->baseUrl = 'http://base.url.com/';
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

	public function testItReturnsOAuthClientClass()
	{
		$this->oauth_client_class = OauthClientTestClass::class;

		$this->assertEquals(OauthClientTestClass::class, $this->oauthClientClass());
	}

	public function testItReturnsOAuthClientClassInstance()
	{
		$this->oauth_client_class = OauthClientTestClass::class;
		$this->assertTrue($this->oauthClientClass(true) instanceof OauthClientTestClass);
	}

	public function testItThrowsUsefulErrorOnOAuthClientClassNotImplemented()
	{
		$this->setExpectedException(\InvalidArgumentException::class, self::class . ' does not implement an OAuth Client class definition.');
		$this->oauthClientClass();
	}

	public function testItReturnsOauthUrlPath()
	{
		$this->oauth_url = 'not_oauth/not_access_token';

		$this->assertEquals('not_oauth/not_access_token', $this->oauthUrl());
	}

	public function testItThrowsUsefulErrorOnOauthUrlNotImplemented()
	{
		$this->setExpectedException(\InvalidArgumentException::class, self::class . ' does not implement an OAuth path definition.');
		$this->oauthUrl();
	}

	public function testItReturnsCorrectAccessToken()
	{
		$this->access_tokens['exampleUser'] = 'access_token1';

		$this->assertEquals('access_token1', $this->getToken('exampleUser'));
	}

	public function testItReturnsBearerTokenString()
	{
		$this->access_tokens['exampleUser'] = 'access_token1';

		$this->assertEquals('Bearer access_token1', $this->getTokenString('exampleUser'));
	}

	public function testItReturnsAuthorizationHeader()
	{
		$this->access_tokens['exampleUser'] = 'access_token1';

		$this->assertEquals(['Authorization' => 'Bearer access_token1'], $this->getAuthorizationHeader('exampleUser'));
	}
}
