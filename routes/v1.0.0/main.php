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


Route::group(['prefix' => 'auth'], function (){
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
});

Route::post('register', 'RegisterController');


Route::group(['prefix' => 'task'], function (){
    Route::post('completed/{task}', 'TaskController@completed');
    Route::post('incomplete/{task}', 'TaskController@incomeplete');
    Route::post('archived/{task}', 'TaskController@archived');
    Route::post('restore/{task}', 'TaskController@restore');
});

Route::resource('task', 'TaskController');

