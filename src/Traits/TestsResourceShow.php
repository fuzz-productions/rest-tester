<?php

namespace Fuzz\RestTests\Resources;

trait TestsResourceShow
{
	/**
	 * @test
	 *
	 * Getting a single resource with {id}.
	 * GET /resources/{id}
	 *
	 * Given
	 *      I want to get resource {id}.
	 * When
	 *      I make a request to GET /resources/{id}
	 * Then
	 *      I should get a Response of OK (200)
	 *      And the resource in the Response's body
	 */
	public function testResourceShow()
	{
		$this->validateImplementation();

		// Given
		$model = $this->create()->fresh();

		// When
		$this->get($this->path($model->getKey()))
			// Then
			->seeStatusCode(200)
			->seeJsonEquals($model->toArray());
	}
}
