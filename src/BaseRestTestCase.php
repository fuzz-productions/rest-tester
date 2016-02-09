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
	 * Stprage for user resource path
	 *
	 * @var string
	 */
	public $users_path = 'users';

	/**
	 * Storage for API version
	 *
	 * @var string
	 */
	public $api_version = null;

	/**
	 * Whether the response has a wrapper (ex: { "data": [ {"id": 1}, {"id": 2} ]
	 * @var string
	 */
	public $response_wrapper = null;

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
	 * Get the application's user resource path
	 *
	 * @return mixed
	 */
	public function usersPath()
	{
		return $this->users_path;
	}

	/**
	 * Parse the last json response
	 *
	 * @param bool $with_wrapper
	 * @return \stdClass
	 */
	protected function getJson($with_wrapper = false)
	{
		if (is_null($this->response_wrapper) || $with_wrapper) {
			return json_decode($this->response->getContent());
		} else {
			return json_decode($this->response->getContent())->{$this->response_wrapper};
		}
	}

	/**
	 * Send a create request to create a user
	 *
	 * $user_data can include 'scope' key to set scopes, assuming User model implements a way to set scopes
	 * in such a way.
	 *
	 * @param array $user_data
	 * @param array $headers
	 * @param bool  $get_model
	 * @return \stdClass
	 */
	public function createUser(array $user_data, $headers = [], $get_model = false)
	{
		// Create as an authenticated user
		if (empty($headers)) {
			$response = $this->post($this->url($this->usersPath()), $user_data)->seeStatusCode(201)->getJson();
		} else {
			$response = $this->post($this->url($this->usersPath()), $user_data, $headers)->seeStatusCode(201)->getJson();
		}

		$user = $this->userClass();

		return $get_model ? $user::find($response->id) : $response;
	}

	/**
	 * Create a user in the database directly
	 *
	 * @param array $user_data
	 * @param bool  $get_model
	 * @return string
	 */
	public function createUserDirect(array $user_data, $get_model = false)
	{
		$user = $this->userClass(true);

		// Set user data
		foreach ($user_data as $attribute => $value) {
			$user->{$attribute} = $value;
		}

		$status = $user->save();

		return $get_model ? $user : $status;
	}

	/**
	 * Read a value for a header that's ready to be sent
	 *
	 * @param string $key
	 * @return null
	 */
	public function getHeaderValue($key)
	{
		if (! empty($this->headers_cache)) {
			return isset($this->headers_cache[$key]) ? $this->headers_cache[$key] : null;
		}

		// Requires XDebug installed
		foreach (xdebug_get_headers() as $header) {
			$split                          = explode(': ', $header); // ['key', 'value']
			$this->headers_cache[$split[0]] = $split[1];
		}

		return isset($this->headers_cache[$key]) ? $this->headers_cache[$key] : null;
	}
}
