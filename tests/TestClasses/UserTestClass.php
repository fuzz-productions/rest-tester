<?php

namespace Fuzz\RestTests\Tests\TestClasses;

use Illuminate\Database\Eloquent\Model;

class UserTestClass extends Model
{
	/**
	 * Fillable fields
	 *
	 * @var array
	 */
	public $fillable = ['sample_field'];
}
