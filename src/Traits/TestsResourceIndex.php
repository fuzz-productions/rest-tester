<?php

namespace Fuzz\RestTests\Resources;

trait TestsResourceIndex
{
	/**
	 * @test
	 *
	 * Getting a collection of resources.
	 * GET /resources
	 *
	 * Given
	 *      I want to get a list of the resources.
	 * When
	 *      I make a request to GET /resources
	 * Then
	 *      I should get a Response of OK (200)
	 *      And a collection (list) of resources in the Response's body
	 */
	public function testResourceIndex()
	{
		$this->validateImplementation();

		// Given
		$this->create(10);
		$model = $this->model(true);
		$collection = $model->get();

		// When
		$this->get($this->path())
			// Then
			->seeStatusCode(200)
			->seeJsonEquals($collection->toArray());
	}
}
