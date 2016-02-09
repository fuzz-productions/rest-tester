<?php

namespace Fuzz\RestTests\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	public function scopes()
	{
		return $this->belongsToMany(OAuthScope::class);
	}
}
