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

Route::group([
    'middleware' => [ 'web', 'api' ],
], function () {
    Route::post('match-create/football', 'ImportController@save');
    Route::get('match-data/football/{externalId}', 'ApiController@get');
    Route::get('match-top/football', 'ApiController@getTop');
    Route::get('match-top/football/{minimumGoals}', 'ApiController@getTop');
    Route::get('match-team/football/{team}', 'ApiController@getForTeam');
    Route::get('match-team/football/{team}/{quantity}', 'ApiController@getForTeam');
});
