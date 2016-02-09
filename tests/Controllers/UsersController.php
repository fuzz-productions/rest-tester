<?php

namespace Fuzz\RestTests\Tests\Controllers;

use Fuzz\RestTests\Tests\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return response()->json(User::all()->toArray());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		// Set headers, if present in the request, so we can test some test utility methods
		//foreach ($request->get('headers', []) as $key => $value) {
		//	header("$key: $value");
		//}

		$headers = $request->header();

		$user = new User($request->all());
		$user->save();
		return response()->json($user->toArray(), 201);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		return response()->json(User::find($id)->toArray());
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param  int                     $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, $id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$user = User::find($id);
		$user->fill($request->all());
		$user->save();
		return response()->json($user->toArray());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		User::destroy($id);

		return response()->json([], 200);
	}
}
