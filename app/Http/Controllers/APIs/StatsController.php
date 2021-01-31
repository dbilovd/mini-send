<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
	/**
	 * Show the stats for the current user
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show()
	{
		$user = User::findOrFail(request()->get("userId"));

		$stats = Cache::remember(
			"stats_for_{$user->id}", 
			(5 * 60), 
			function () use ($user) {
				return [
					"total" 	=> $user->messages()->count(),
					"pending" 	=> $user->messages()->filteredByStatus("pending")->count(),
					"failed" 	=> $user->messages()->filteredByStatus("failed")->count(),
					"sent" 		=> $user->messages()->filteredByStatus("sent")->count(),
					"updatedAt" => now()
				];
			}
		);

		return $this->successResponse($stats);
	}
}
