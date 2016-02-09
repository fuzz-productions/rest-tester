<?php

namespace Fuzz\RestTests\Tests\Providers;

use Fuzz\RestTests\Tests\Controllers\Controller;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// Do nothing
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => 'Fuzz\\RestTests\\Tests\\Controllers', 'prefix' => Controller::API_VERSION], function (Router $router) {
			$router->resource('users', 'UsersController');
		});
	}
}
