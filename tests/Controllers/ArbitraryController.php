<?php

namespace Fuzz\RestTests\Tests\Controllers;

use Illuminate\Http\Request;

class ArbitraryController extends Controller
{
	/**
	 * Set some headers that we can test
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function headers(Request $request)
	{
		foreach ($request->get('headers', []) as $key => $value) {
			header("$key: $value");
		}

		return response()->json([], 200);
	}

	/**
	 * Process received headers and return them as a response so a test can confirm
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function testUserHeaders(Request $request)
	{
		return response()->json([
			'received_headers' => $request->header(),
		], $request->get('status_code', 200));
	}

	/**
	 * Process requested response wrapper and return a fake response wrapped with it so
	 * a test can confirm
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function withWrapper(Request $request)
	{
		return response()->json([
			$request->get('wrapper') => $request->get('requested_response_data'),
		]);
	}
}
