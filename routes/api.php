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

<<<<<<< HEAD
Route::post('users/updateLicense', 'UserController@updateLicense');
Route::post('users/login', 'UserController@login');
Route::post('users/register', 'UserController@register');
Route::post('post', 'UserController@post');
Route::get('users/get/{id}', 'UserController@get');
Route::patch('users/patch/{id}', 'UserController@update');
Route::delete('users/delete/{id}', 'UserController@delete');
=======
Route::post('users/register', 'UserController@register');
Route::post('users/login', 'UserController@login');
Route::middleware('auth:api')->group(function () {
        Route::post('users/updateLicense', 'UserController@updateLicense');
        Route::post('post', 'UserController@post');
        Route::get('users/get/{id}', 'UserController@get');
        Route::patch('users/patch/{id}', 'UserController@update');
        Route::delete('users/delete/{id}', 'UserController@delete');
        Route::get('country', 'UserController@listCountry');
        Route::get('users/logout', 'UserController@logout');
        Route::post('country/get', 'UserController@getCountry');
});
//Admin
Route::resource('users','Admin\UserController');
Route::post('users/country', 'Admin\UserController@getCountry')->name('users.country');

>>>>>>> 29b43b86229b8c31f6cceb3709c8fc2790350a03
