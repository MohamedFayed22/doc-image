<?php

use Illuminate\Http\Request;

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

Route::group(['middleware'=> ['cors']], function(){
    Route::post('/save-image', 'ImageController@newImage');
    Route::post('/image/store', 'ImageController@store');
    Route::post('/search', 'ImageController@search');
    Route::post('/latest', 'ImageController@latestDoc');
    Route::post('/data-image', 'ImageController@getImage');
});
