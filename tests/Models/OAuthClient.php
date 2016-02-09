<?php

namespace Fuzz\RestTests\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthClient extends Model
{
	public $incrementing = false;

	/**
	 * User relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function scopes()
	{
		return $this->belongsToMany(OAuthScope::class, 'oauth_client_scopes', 'client_id', 'scope_id');
	}
}
