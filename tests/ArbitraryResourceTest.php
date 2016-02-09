<?php

namespace Fuzz\RestTests\Tests;

use Fuzz\RestTests\Resources\RestfulResource;
use Fuzz\RestTests\Tests\Models\Arbitrary;
use Fuzz\RestTests\Tests\Models\User;

class ArbitraryResourceTest extends ApiResourceTestCase
{
	use RestfulResource;

	public $model = Arbitrary::class;

	public $resource_path = 'arbitrary';

	/**
	 * Runs in separate process so we can correctly check for headers
	 *
	 * @runInSeparateProcess
	 * @todo process isolation causes some Laravel bindings to not correctly resolve/instantiate. Why?
	 */
	public function testItCanCreateUsersViaHTTPAndSendHeaders()
	{
		$this->user_class = User::class;
		$this->users_path = 'arbitrary/test-user-headers';

		$user_data = [
			'username'    => 'Username',
			'password'    => 'password',
			'status_code' => 201,
		];

		$headers = [
			'X-Arbitrary'     => 'Arbitrary value',
			'X-Non-Arbitrary' => 'Non Arbitrary Value',
		];

		$this->createUser($user_data, $headers);

		// Laravel formats the return from Request::header() differently, so adjust return expectation
		$this->seeJson([
			'x-arbitrary'     => ['Arbitrary value'],
			'x-non-arbitrary' => ['Non Arbitrary Value'],
		]);
	}

	/**
	 * Runs in separate process so we can correctly check for headers
	 *
	 * @runInSeparateProcess
	 */
	public function testItCanFindCorrectHeaderValues()
	{
		// Controller is set up to set input as headers
		$headers_input = [
			'X-Arbitrary'     => 'Arbitrary value',
			'X-Non-Arbitrary' => 'Non Arbitrary Value',
		];

		// Sends headers that the controller will set for its response
		$this->post($this->path('headers'), ['headers' => $headers_input]);

		foreach ($headers_input as $key => $value) {
			$this->assertEquals($value, $this->getHeaderValue($key));
		}
	}

	public function testItCanCreateUserViaHTTP()
	{
		$this->user_class = User::class;

		$user_data = [
			'username' => 'Username',
			'password' => 'password'
		];

		$this->createUser($user_data);

		$this->seeJson($user_data)->seeInDatabase('users', $user_data);
	}

	public function testItCanCreateUserViaHTTPAndReturnInstance()
	{
		$this->user_class = User::class;

		$user_data = [
			'username' => 'Username',
			'password' => 'password'
		];

		$user = $this->createUser($user_data, [], true);

		$this->seeJson($user_data)->seeInDatabase('users', $user_data);

		$this->assertTrue($user instanceof User);
	}

	public function testItCanCreateUserDirectly()
	{
		$this->user_class = User::class;

		$user_data = [
			'username' => 'Username',
			'password' => 'password'
		];

		$this->assertTrue($this->createUserDirect($user_data));
	}

	public function testItCanCreateUserDirectlyAndReturnInstance()
	{
		$this->user_class = User::class;

		$user_data = [
			'username' => 'Username',
			'password' => 'password'
		];

		$user =$this->createUserDirect($user_data, true);

		$this->assertTrue($user instanceof User);
	}

	public function testItReturnsResponseDataFromWrapper()
	{
		$this->response_wrapper = 'data_wrapper';

		$requested_response_data = [
			'item1' => 'value1',
			'item2' => 'value2',
		];

		$this->post($this->path('response-with-wrapper'), ['wrapper' => 'data_wrapper', 'requested_response_data' => $requested_response_data])
			->seeJson([
				'data_wrapper' => $requested_response_data,
			]);

		// convert to array
		$response = json_decode(json_encode($this->getJson()), true);
		$this->assertEquals($requested_response_data, $response);

		// Get the original wrapped response
		$response = json_decode(json_encode($this->getJson(true)), true);
		$this->assertEquals(['data_wrapper' => $requested_response_data], $response);
	}
}
