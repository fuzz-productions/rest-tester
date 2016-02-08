<?php

namespace Fuzz\RestTests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
	 * Configuration for OAuth Client class
	 *
	 * @var null|string
	 */
	public $oauth_client_class = null;

	/**
	 * Base OAuth 2.0 token request URL
	 *
	 * @var string
	 */
	public $oauth_url = null;

	/**
	 * Storage for access token
	 *
	 * @var array
	 */
	public $access_tokens = [];

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
	 * Get the applications OAuth client class
	 *
	 * @param bool $instance
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	public function oauthClientClass($instance = false)
	{
		if (is_null($this->oauth_client_class)) {
			throw new \InvalidArgumentException(static::class . ' does not implement an OAuth Client class definition.');
		}

		return $instance ? new  $this->oauth_client_class : $this->oauth_client_class;
	}

	/**
	 * Get the OAuth path
	 *
	 * @return string
	 */
	public function oauthUrl()
	{
		if (is_null($this->oauth_url)) {
			throw new \InvalidArgumentException(static::class . ' does not implement an OAuth path definition.');
		}

		return $this->oauth_url;
	}

	/**
	 * Send an auth request and return the json data as a stdClass object
	 *
	 * @param string $username
	 * @param string $password
	 * @param array  $scopes
	 * @param array  $client
	 * @param string $grant_type
	 * @param bool   $is_email
	 * @return \stdClass
	 */
	public function authenticate($username, $password, array $scopes = [], array $client = [], $grant_type = 'password', $is_email = false)
	{
		$scope = implode(',', $scopes);

		$request_data = [
			'password' => $password,
			'client_id' => $client['client_id'],
			'client_secret' => $client['client_secret'],
			'grant_type' => $grant_type,
			'scope' => $scope,
		];

		if ($is_email) {
			$request_data['email'] = $username;
		} else {
			$request_data['username'] = $username;
		}

		$response = $this->post($this->oauthUrl(), $request_data)->getJson();

		// If this was a successful auth, set the access token
		if (isset($response->access_tokens)) {
			$this->access_tokens[$username] = $response->access_tokens;
		}

		return $response;
	}

	/**
	 * Retrieve an access token
	 *
	 * @param $username
	 * @return string
	 */
	public function getToken($username) {
		return isset($this->access_tokens[$username]) ? $this->access_tokens[$username] : null;
	}

	/**
	 * Get a string including the access token to pass through to an Authorization header
	 *
	 * @param $username
	 * @return string
	 */
	public function getTokenString($username) {
		return 'Bearer ' . $this->getToken($username);
	}

	/**
	 * Get the full Authorization header in array form
	 *
	 * @param $username
	 * @return array
	 */
	public function getAuthorizationHeader($username) {
		return ['Authorization' => $this->getTokenString($username)];
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
			$response = $this->post($this->url('users'), $user_data, $headers)
				->seeStatusCode(201)
				->getJson();
		} else {
			$response = $this->post($this->url('users'), $user_data)
				->seeStatusCode(201)
				->getJson();
		}

		$user = $this->userClass();
		return $get_model ? $user::find($response->data->id) : $response;
	}

	/**
	 * Attach scopes to a user
	 *
	 * @param object $user
	 * @param array $scopes
	 */
	public function applyScopesToUser($user, array $scopes)
	{
		if (! is_a($user, Model::class)) {
			$this->fail('User is not a model.');
		}

		$scopes = array_map(function ($scope) {
			return ['oauth_scope_id' => $scope];
		}, $scopes);

		$user->scopes()->attach($scopes);

		return $user->fresh(['scopes']);
	}

	/**
	 * Create a client and give it some scopes
	 *
	 * @param string $id
	 * @param string $secret
	 * @param array $scopes
	 *
	 * @return object
	 */
	public function createClientWithScopes($id, $secret, array $scopes)
	{
		$client = $this->oauthClientClass(true);
		$client->id = $id;
		$client->secret = $secret;
		$client->name = $id;
		$client->save();

		$apply_scopes = [];
		foreach ($scopes as $scope) {
			$apply_scopes[] = [
				'client_id' => $id,
				'scope_id' => $scope,
			];
		}

		DB::table('oauth_client_scopes')->insert($apply_scopes);
		return $client;
	}
}
