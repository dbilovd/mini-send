<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Return a successful response for a paginated result set
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginatedSuccessResponse($paginatedResult)
    {
    	$data = $paginatedResult->map(function ($item) {
			return method_exists($item, 'formatForApi') ?
				$item->formatForApi() : $item;
		});

		$meta = [
            'links' => [
                'first'	=> $paginatedResult->url(1),
                'last' 	=> $paginatedResult->url($paginatedResult->lastPage()),
                'prev' 	=> $paginatedResult->previousPageUrl(),
                'next' 	=> $paginatedResult->nextPageUrl(),
            ],
            'meta' => [
                'current_page' 	=> $paginatedResult->currentPage(),
                'from' 			=> $paginatedResult->firstItem(),
                'last_page' 	=> $paginatedResult->lastPage(),
                'path' 			=> $paginatedResult->resolveCurrentPath(),
                'per_page' 		=> $paginatedResult->perPage(),
                'to' 			=> $paginatedResult->lastItem(),
                'total' 		=> $paginatedResult->total(),
            ],
        ];

    	return $this->successResponse(
    		$data,
			200,
			'Fetched messages',
			$meta
    	);
    }

    /**
     * Return a successful response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data = null, $status = 200, $message = "Success", $meta = null)
    {
    	$status = $status ?: 200;
    	$response = [
			'code'		=> $status,
			'message'	=> $message
    	];

    	if (!is_null($data)) {
    		$response["data"] = $data;
    	}

    	if ($meta) {
    		$response["meta"] = $meta;
    	}

		return response()->json($response, $status);
    }

    /**
     * Return a failed response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($status = 500, $message = "Success")
    {
		return response()->json([
			'code'		=> $status,
			'message'	=> $message
		], $status);
    }
}
