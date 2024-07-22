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

Route::group([

    'middleware' => 'api',

], function ($router) {
    Route::post('send-email', array('middleware' => 'cors', 'uses' => 'Controller@sendEmail'));
    Route::post('check-auth', array('middleware' => 'cors', 'uses' => 'Controller@checkAuth'));
    Route::post('edit', array('middleware' => 'cors', 'uses' => 'Controller@edit'));
});
