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

Route::post('register/group', 'GroupController@register');
Route::post('register/admin', 'UsersController@store');

Route::group(['middleware' => 'auth:api'], function() {
  Route::post('validate/otp', 'UsersController@validateOTP');
  Route::post('resend/otp/{mobile_no}', 'UsersController@resendOTP');
  Route::post('register/accounts/{group}', 'GroupAccountController@store');
});
