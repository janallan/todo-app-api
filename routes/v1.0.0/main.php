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
    Route::get('profile', 'AuthController@profile');
});

Route::post('register', 'RegisterController');


Route::group(['prefix' => 'task'], function (){
    Route::post('completed/{task}', 'TaskController@completed');
    Route::post('incomplete/{task}', 'TaskController@incomeplete');
    Route::post('archived/{task}', 'TaskController@archived');
    Route::post('restore/{task}', 'TaskController@restore');
    Route::get('{task}/tags/available', 'TaskController@availableTags');
    Route::post('{task}/tags', 'TaskController@setTags');
    Route::post('{task}/tags/add/{tagName}', 'TaskController@addTag');
    Route::post('{task}/tags/remove/{tagName}', 'TaskController@removeTag');
    Route::post('{task}/attachments/upload', 'TaskController@uploadAttachments');
    Route::post('{task}/attachments/delete/{media}', 'TaskController@deleteAttachment');
    Route::post('{task}/attachments/download/{media}', 'TaskController@downloadAttachment');
});

Route::resource('task', 'TaskController')->except(['create', 'edit']);
Route::resource('tag', 'TagController')->except(['create', 'edit']);

Route::get('attachment/download/{media}', 'DownloadController@attachment')->name('download.task-attachment')->middleware('signed');
