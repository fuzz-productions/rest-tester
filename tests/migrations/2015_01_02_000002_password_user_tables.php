<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class PasswordUserTables extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(
			'users', function ($table) {
			$table->string('username', 132)->nullable();
			$table->string('password', 60)->nullable();
		}
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table(
			'users', function ($table) {
			//$table->dropColumn('username');
			//$table->dropColumn('password');
		}
		);
	}
}
