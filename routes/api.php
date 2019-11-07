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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('users/updateLicense', 'UserController@updateLicense');
Route::post('users/login', 'UserController@login');
Route::post('users/register', 'UserController@register');
Route::post('post', 'UserController@post');
Route::get('users/get/{id}', 'UserController@get');
Route::patch('users/patch/{id}', 'UserController@update');
Route::delete('users/delete/{id}', 'UserController@delete');
