<?php

namespace Fuzz\RestTests\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthScope extends Model
{
	public $incrementing = false;

	/**
	 * User relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany(User::class);
	}

	/**
	 * Clients relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function clients()
	{
		return $this->belongsToMany(OAuthClient::class, 'oauth_client_scopes', 'scope_id', 'client_id');
	}
}
