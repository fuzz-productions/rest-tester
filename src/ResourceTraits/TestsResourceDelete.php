<?php

namespace Fuzz\RestTests\Resources;

trait TestsResourceDelete
{
	/**
	 * @test
	 *
	 * Deleting an existing resource with {id}.
	 * DELETE /resources/{id}
	 *
	 * Given
	 *      I want to delete resource {id}
	 * When
	 *      I make a request to DELETE /resources/{id}
	 * Then
	 *      The resource should be deleted in the database
	 *      And I should get a Response of OK (200)
	 *      And the Response's body should be empty
	 */
	public function testResourceDelete()
	{
		$this->validateImplementation();

		// Given
		$model = $this->create()->fresh();
		$this->makeGenericUpdate($model)->assertTrue($model->isDirty()); // Is updated

		// When
		$this->delete($this->path($model->getKey()), $model->toArray())
			// Then
			->seeStatusCode(200)
			->dontSeeInDatabase($this->table(), $model->toArray());
	}
}
