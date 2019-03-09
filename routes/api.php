<?php

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

Route::post('register', 'API\AuthController@register');
Route::post('login', 'API\AuthController@login');
Route::post('logout', 'API\AuthController@logout');

//trrdy
Route::get('bikecomment', 'API\BikedetailController@index');
Route::post('bikecomment', 'API\BikedetailController@store');

Route::apiResources([
    'bikes' => 'API\BikeController',
    'comments' => 'API\CommentController',
    'ratings' => 'API\RatingController',
]);

Route::middleware('jwt.auth')->get('users', function (Request $request) {
    return auth()->user();
});
