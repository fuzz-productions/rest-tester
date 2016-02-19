<?php

namespace Fuzz\RestTests\AuthTraits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait OAuthTrait
{
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
			'password'      => $password,
			'client_id'     => $client['client_id'],
			'client_secret' => $client['client_secret'],
			'grant_type'    => $grant_type,
			'scope'         => $scope,
		];

		if ($is_email) {
			$request_data['email'] = $username;
		} else {
			$request_data['username'] = $username;
		}

		$response = $this->post($this->oauthUrl(), $request_data)->getJson();

		// If this was a successful auth, set the access token
		if (isset($response->access_token)) {
			$this->access_tokens[$username] = $response->access_token;
		}

		return $response;
	}

	/**
	 * Send an auth request and return the json data as a stdClass object
	 *
	 * @param        $refresh_token
	 * @param array  $client
	 * @param string $grant_type
	 * @param string $username
	 * @param array  $scopes
	 * @return \stdClass
	 */
	public function refreshToken($refresh_token, array $client = [], $grant_type = 'refresh_token', $username = '', $scopes = [])
	{
		$request_data = [
			'client_id'     => $client['client_id'],
			'client_secret' => $client['client_secret'],
			'grant_type'    => $grant_type,
			'refresh_token' => $refresh_token,
		];

		if (! empty($scopes)) {
			$request_data['scope'] = implode(',', $scopes);
		}

		$response = $this->post($this->oauthUrl(), $request_data)->getJson();

		// If this was a successful auth, set the access token
		if (isset($response->access_token)) {
			$this->access_tokens[$username] = $response->access_token;
		}

		return $response;
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
		if (! isset($this->oauth_client_class)) {
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
		if (! isset($this->oauth_url)) {
			throw new \InvalidArgumentException(static::class . ' does not implement an OAuth path definition.');
		}

		return $this->oauth_url;
	}

	/**
	 * Retrieve an access token
	 *
	 * @param $username
	 * @return string
	 */
	public function getToken($username)
	{
		return isset($this->access_tokens[$username]) ? $this->access_tokens[$username] : null;
	}

	/**
	 * Get a string including the access token to pass through to an Authorization header
	 *
	 * @param $username
	 * @return string
	 */
	public function getTokenString($username)
	{
		return 'Bearer ' . $this->getToken($username);
	}

	/**
	 * Get the full Authorization header in array form
	 *
	 * @param $username
	 * @return array
	 */
	public function getAuthorizationHeader($username)
	{
		return ['Authorization' => $this->getTokenString($username)];
	}

	/**
	 * Attach scopes to a user
	 *
	 * @param object $user
	 * @param array  $scopes
	 */
	public function applyScopesToUser($user, array $scopes)
	{
		if (! is_a($user, Model::class)) {
			$this->fail('User is not a model.');
		}

		$scopes = array_map(
			function ($scope) {
				return ['oauth_scope_id' => $scope];
			}, $scopes
		);

		$user->scopes()->attach($scopes);

		return $user->fresh(['scopes']);
	}

	/**
	 * Create a client and give it some scopes
	 *
	 * @param string $id
	 * @param string $secret
	 * @param array  $scopes
	 *
	 * @return object
	 */
	public function createClientWithScopes($id, $secret, array $scopes)
	{
		$client         = $this->oauthClientClass(true);
		$client->id     = $id;
		$client->secret = $secret;
		$client->name   = $id;
		$client->save();

		$apply_scopes = [];
		foreach ($scopes as $scope) {
			$apply_scopes[] = [
				'client_id' => $id,
				'scope_id'  => $scope,
			];
		}

		DB::table('oauth_client_scopes')->insert($apply_scopes);

		return $client;
	}
}
