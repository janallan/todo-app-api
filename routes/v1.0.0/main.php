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
    Route::post('completed/{taskId}', 'TaskController@completed');
    Route::post('todo/{taskId}', 'TaskController@todo');
    Route::post('archived/{taskId}', 'TaskController@archived');
    Route::post('restore/{taskId}', 'TaskController@restore');
});

Route::resource('task', 'TaskController');

