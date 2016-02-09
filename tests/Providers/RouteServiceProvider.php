<?php

namespace Fuzz\RestTests\Tests\Providers;

use Fuzz\RestTests\Tests\Controllers\Controller;
//use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;

class RouteServiceProvider extends BaseRouteServiceProvider
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
			$router->post(
				'oauth/access_token', 'Fuzz\ApiServer\Routing\OAuthController@issueAccessToken'
			);

			$router->resource('users', 'UsersController');
			$router->resource('arbitrary', 'ArbitraryController');
			$router->post('arbitrary/headers', 'ArbitraryController@headers');
			$router->post('arbitrary/test-user-headers', 'ArbitraryController@testUserHeaders');
			$router->post('arbitrary/response-with-wrapper', 'ArbitraryController@withWrapper');
		});
	}
}
