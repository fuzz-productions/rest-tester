<?php

namespace Fuzz\RestTests\Tests;

use Fuzz\RestTests\AuthTraits\OAuthTrait;
use Fuzz\RestTests\Tests\TestClasses\OauthClientTestClass;

class OAuthTraitTest extends BaseTestCase
{
	use OAuthTrait;

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
