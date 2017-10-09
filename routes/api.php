<?php

use Illuminate\Http\Request;
Use App\Document;

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

Route::group([
	'prefix' => 'v1',
	'as' => 'api::v1::',
	'middleware' => 'OpenCors'
], function () {
	Route::group([
		'prefix' => 'documento',
		'as' => 'document::'
	], function () {
		Route::get('', 'SearchController@search');
		Route::post('', 'DocumentController@create');
		Route::put('{id}', 'DocumentController@update');
		Route::get('{id}.pdf', 'DocumentController@pdf');
	});
});
