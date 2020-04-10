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


Route::group(['prefix' => 'myapp'], function () {
    Route::post('/login', 'UserController@login');
    Route::post('/register', 'UserController@register');
    Route::post('/details', 'UserController@details')->middleware('auth:api');
    Route::get('/logout', 'UserController@logout')->middleware('auth:api');
});