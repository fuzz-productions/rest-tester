<?php

namespace Fuzz\RestTests\Tests;

use Fuzz\RestTests\BaseRestTestCase;
use Fuzz\RestTests\Tests\Providers\RouteServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider;
use LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider;
use Mockery;
use Orchestra\Testbench\Traits\ApplicationTrait;

abstract class ApiResourceTestCase extends BaseRestTestCase
{
	use ApplicationTrait;

	/**
	 * The base URL to use while testing the application.
	 *
	 * @var string
	 */
	protected $baseUrl = 'http://localhost';

	/**
	 * The Eloquent factory instance.
	 *
	 * @var \Illuminate\Database\Eloquent\Factory
	 */
	protected $factory;

	/**
	 * Command kernel.
	 *
	 * @var \Illuminate\Contracts\Console\Kernel
	 */
	protected $artisan;

	/**
	 * The callbacks that should be run before the application is destroyed.
	 *
	 * @var array
	 */
	protected $beforeApplicationDestroyedCallbacks = [];

	/**
	 * Get base path.
	 *
	 * @return string
	 */
	protected function getBasePath()
	{
		// reset base path to point to our package's src directory
		return __DIR__ . '/../vendor/orchestra/testbench/fixture';
	}

	/**
	 * Setup the test environment.
	 *
	 * @return void
	 */
	public function setUp()
	{
		if (! $this->app) {
			$this->refreshApplication();
		}

		if (! $this->factory) {
			$this->factory = $this->app->make(Factory::class);
		}

		// Own
		$this->artisan = $this->app->make('Illuminate\Contracts\Console\Kernel');

		$this->artisan->call('migrate', [
			'--database' => 'testbench',
			'--realpath'     => realpath(__DIR__ . '/migrations'),
		]);
	}

	/**
	 * Clean up the testing environment before the next test.
	 */
	public function tearDown()
	{
		$this->artisan->call('migrate:rollback', ['--database' => 'testbench']);

		if (class_exists('Mockery')) {
			Mockery::close();
		}

		if ($this->app) {
			foreach ($this->beforeApplicationDestroyedCallbacks as $callback) {
				call_user_func($callback);
			}

			$this->app->flush();

			$this->app = null;
		}

		if (property_exists($this, 'serverVariables')) {
			$this->serverVariables = [];
		}
	}

	/**
	 * Register a callback to be run before the application is destroyed.
	 *
	 * @param  callable  $callback
	 *
	 * @return void
	 */
	protected function beforeApplicationDestroyed(callable $callback)
	{
		$this->beforeApplicationDestroyedCallbacks[] = $callback;
	}

	/**
	 * Define environment setup.
	 *
	 * @param  \Illuminate\Foundation\Application   $app
	 *
	 * @return void
	 */
	protected function getEnvironmentSetUp($app)
	{
		$app['config']->set('database.default', 'testbench');
		$app['config']->set('database.connections.testbench', [
			'driver'   => 'sqlite',
			'database' => ':memory:',
			'prefix'   => '',
		]);
	}

	/**
	 * Get package providers.
	 *
	 * @param  \Illuminate\Foundation\Application $app
	 * @return array
	 */
	protected function getPackageProviders($app)
	{
		return [
			RouteServiceProvider::class,
			FluentStorageServiceProvider::class,
			OAuth2ServerServiceProvider::class
		];
	}

	/**
	 * Seed data.
	 *
	 * @return void
	 */
	protected function performSeed()
	{
		$this->artisan->call('db:seed', [
			'--database' => 'testbench',
			'--class'    => 'TestDatabaseSeeder'
		]);
	}
}
