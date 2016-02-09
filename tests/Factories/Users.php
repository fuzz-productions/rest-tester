<?php

$factory->define(\Fuzz\RestTests\Tests\Models\User::class, function (Faker\Generator $faker) {
	return [
		'username' => $faker->userName,
		'password' => bcrypt($faker->password(6)),
	];
});
