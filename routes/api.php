<?php

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


Route::prefix('/sources')->group(function () {
    Route::get('/', 'SourceController@index');
    Route::get('/{source}', 'SourceController@view');
});
Route::prefix('/coins')->group(function () {
    Route::get('/', 'CoinController@index');
    Route::get('/latest', 'CoinController@latest');
    Route::get('/{coin}', 'CoinController@view');
});


/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

*/