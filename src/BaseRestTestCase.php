<?php

namespace Fuzz\RestTests;

class BaseRestTestCase extends BaseFuzzTestCase
{
	/**
	 * The base URL to use while testing the application.
	 *
	 * @var null|string
	 */
	protected $baseUrl = null;

	/**
	 * Storage for user class
	 *
	 * @var null|string
	 */
	public $user_class = null;

	/**
	 * Storage for API version
	 *
	 * @var string
	 */
	public $api_version = null;

	/**
	 * Get the API version
	 *
	 * @return string
	 */
	public function apiVersion()
	{
		if (is_null($this->api_version)) {
			throw new \InvalidArgumentException(static::class . ' does not implement an API version definition.');
		}

		return $this->api_version;
	}

	/**
	 * Get a URL for an API endpoint
	 *
	 * @param $path
	 * @return string
	 */
	public function url($path)
	{
		if (is_null($this->baseUrl)) {
			throw new \InvalidArgumentException(static::class . ' does not implement a base url definition.');
		}

		return $this->apiVersion() . '/' . $path;
	}

	/**
	 * Get the application's user class
	 *
	 * @param bool $instance
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	public function userClass($instance = false)
	{
		if (is_null($this->user_class)) {
			throw new \InvalidArgumentException(static::class . ' does not implement a User class definition.');
		}

		return $instance ? new  $this->user_class : $this->user_class;
	}

	/**
	 * Parse the last json response
	 *
	 * @return mixed
	 */
	protected function getJson()
	{
		return json_decode($this->response->getContent());
	}

	/**
	 * Send a create request as an admin
	 *
	 * $user_data can include 'scope' key to set scopes, assuming User model implements a way to set scopes
	 * in such a way.
	 *
	 * @param array $user_data
	 * @param array $headers
	 * @param bool  $get_model
	 * @return \stdClass
	 */
	public function createUser($user_data, $headers = [], $get_model = false)
	{
		// Create as an authenticated user
		if (empty($headers)) {
			$response = $this->post($this->url('users'), $user_data, $headers)->seeStatusCode(201)->getJson();
		} else {
			$response = $this->post($this->url('users'), $user_data)->seeStatusCode(201)->getJson();
		}

		$user = $this->userClass();

		return $get_model ? $user::find($response->data->id) : $response;
	}
}
