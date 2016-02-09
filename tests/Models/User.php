<?php

namespace Fuzz\RestTests\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	/**
	 * Be naughty and allow every attribute to be fillable
	 *
	 * @var array
	 */
	protected $fillable = ['username', 'password'];

	/**
	 * Hide dates
	 *
	 * @var array
	 */
	protected $hidden = ['created_at', 'updated_at'];

	public function scopes()
	{
		return $this->belongsToMany(OAuthScope::class);
	}
}
