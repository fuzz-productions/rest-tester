<?php

namespace Fuzz\RestTests\Resources;

trait TestsResourceCreate
{
	/**
	 * @test
	 *
	 * Creating a new resource.
	 * POST /resources
	 *
	 * Given
	 *      I want to create a new resource.
	 * When
	 *      I make a request to POST /resources with a new resource
	 * Then
	 *      The new resource should be saved in the database
	 *      And I should get a Response of Created (201)
	 *      And the resource in the Response's body
	 *      And the Location header should contain the resources URI
	 *
	 * @todo implement check for Location header + follow it to make sure it's valid
	 */
	public function testResourceCreate()
	{
		$this->validateImplementation();

		// Given
		$model = $this->make();

		// When
		$this->post($this->path(), $model->toArray())
			// Then
			->seeStatusCode(201)
			// We expect to see a response with all the model's attributes, plus an ID that we shouldn't try to
			// guess
			->seeJson($model->toArray())
			->seeInDatabase($this->table(), $model->toArray());
	}
}
