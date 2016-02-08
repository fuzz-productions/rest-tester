<?php

namespace Fuzz\RestTests\Resources;

trait TestsResourceUpdate
{
	/**
	 * @test
	 *
	 * Updating resource {id}.
	 * PUT /resources/{id}
	 *
	 * Given
	 *      I want to update resource {id}
	 * When
	 *      I make a request to PUT /resources/{id} with updated data
	 * Then
	 *      The resource should be updated in the database
	 *      I should get a Response of OK (200)
	 *      And the updated resource in the Response's body
	 */
	public function testResourceUpdate()
	{
		$this->validateImplementation();

		// Given
		$model = $this->create()->fresh();
		$this->makeGenericUpdate($model)->assertTrue($model->isDirty()); // Is updated

		// When
		$this->put($this->path($model->getKey()), $model->toArray())
			// Then
			->seeStatusCode(200)
			->seeJsonEquals($model->toArray())
			->seeInDatabase($this->table(), [$model->getKeyName() => $model->getKey()]);
	}
}
