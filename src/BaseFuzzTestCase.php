<?php

namespace Fuzz\RestTests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class BaseFuzzTestCase extends TestCase
{
	/**
	 * Creates the application.
	 *
	 * Needs to be implemented by subclasses bootstrap is loaded relative to the test file.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$app = require __DIR__ . '/../bootstrap/app.php';

		$app->make(Kernel::class)->bootstrap();

		return $app;
	}

	/**
	 * Set up test environment
	 *
	 * @todo this can probably be done better
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();

		DB::connection()->disableQueryLog();

		// ENV vars are set in phpunit.xml in the root project folder
		Artisan::call('migrate');
		Artisan::call('db:seed');
	}

	/**
	 * Tear down the test environment
	 *
	 * @return void
	 */
	public function tearDown()
	{
		Artisan::call('migrate:rollback');

		$this->beforeApplicationDestroyed(function () {
			DB::disconnect();
		});

		parent::tearDown();
	}
}
