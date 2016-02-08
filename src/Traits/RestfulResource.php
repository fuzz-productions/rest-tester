<?php

namespace Fuzz\RestTests\Resources;

use Illuminate\Database\Eloquent\Model;

trait RestfulResource
{
	/**
	 * Make sure a model has been defined for this restful resource
	 *
	 * @return void
	 */
	public function validateImplementation()
	{
		$required = [
			'model'         => ' does not implement a model definition.',
			'resource_path' => ' does not implement a resource path definition.',
		];

		foreach ($required as $implementation => $message) {
			if (! isset($this->{$implementation})) {
				throw new \InvalidArgumentException(static::class . $message);
			}
		}
	}

	/**
	 * Get the model class
	 *
	 * @param bool $instance
	 * @return string|\Illuminate\Database\Eloquent\Model
	 */
	public function model($instance = false)
	{
		if (! isset($this->model)) {
			throw new \InvalidArgumentException(static::class . ' does not implement a model definition.');
		}

		return $instance ? new $this->model : $this->model;
	}

	/**
	 * Get the model table
	 *
	 * @return string
	 */
	public function table()
	{
		if (! isset($this->model)) {
			throw new \InvalidArgumentException(static::class . ' does not implement a model definition.');
		}

		return $this->model(true)->getTable();
	}

	/**
	 * Find the resource path
	 *
	 * @param null|int $id
	 * @return string
	 */
	public function path($id = null)
	{
		if (! isset($this->resource_path)) {
			throw new \InvalidArgumentException(static::class . ' does not implement a resource path definition.');
		}

		return is_null($id) ? $this->url($this->resource_path) : $this->url("$this->resource_path/$id");
	}

	/**
	 * Create $amount of this resource and save to the database
	 *
	 * @param int   $amount
	 * @param array $attributes
	 * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
	 */
	public function create($amount = 1, array $attributes = [])
	{
		$this->validateImplementation();

		return factory($this->model, $amount)->create($attributes);
	}

	/**
	 * Create $amount of this resource but don't save to the database
	 *
	 * @param int   $amount
	 * @param array $attributes
	 * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
	 */
	public function make($amount = 1, array $attributes = [])
	{
		$this->validateImplementation();

		return factory($this->model, $amount)->make($attributes);
	}

	/**
	 * Makes a generic update on the first fillable attribute but avoiding the
	 * primary key.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @return $this
	 *
	 */
	protected function makeGenericUpdate(Model $model)
	{
		// Get first fillable key that is not the models primary key.
		$field = array_first(
			$model->getFillable(), function ($key, $value) use ($model) {
			return ($value !== $model->getKeyName());
		}
		);

		switch (gettype($model->{$field})) {
			case 'string':
				$model->{$field} = $model->{$field} . ' updated';
				break;
			case 'boolean':
				$model->{$field} = ! $model->{$field};
				break;
			case 'integer':
			case 'NULL':
			default:
				$model->{$field} = $model->{$field} + 1;
		}

		return $this;
	}
}
