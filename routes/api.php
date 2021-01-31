<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
	// 'middleware'	=> [ 'auth:api' ]
], function () {
	Route::group([
		'prefix'	=> "/messages"
	], function () {
		Route::get("/{message}", [
			"as"	=> "apis.messages.show",
			"uses"	=> "APIs\MessagesController@show"
		]);

		Route::get("/", [
			"as"	=> "apis.messages.index",
			"uses"	=> "APIs\MessagesController@index"
		]);

		Route::post("/", [
			"as"	=> "apis.messages.store",
			"uses"	=> "APIs\MessagesController@store"
		]);
	});

	Route::post("/attachments", [
		"as"	=> "apis.attachments.store",
		"uses"	=> "APIs\AttachmentsController@store"
	]);

	Route::get("/stats", [
		"as"	=> "apis.stats.show",
		"uses"	=> "APIs\StatsController@show"
	]);
});
