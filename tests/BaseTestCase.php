<?php

namespace Fuzz\RestTests\Tests;

use Fuzz\RestTests\BaseRestTestCase;

class BaseTestCase extends BaseRestTestCase
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
		// Do nothing
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
		// Do nothing
	}

	/**
	 * Tear down the test environment
	 *
	 * @return void
	 */
	public function tearDown()
	{
		// Do nothing
	}
}
