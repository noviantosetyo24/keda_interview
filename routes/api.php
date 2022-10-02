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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'App\Http\Controllers\Auth\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\Auth\AuthController@logout');
});

Route::group(['prefix' => 'app', 'middleware' => 'jwt.verify'], function () {
    Route::get('userlist', 'App\Http\Controllers\Main\ApiController@getUserList');

    Route::group(['prefix' => 'customer', 'middleware' => 'role'], function () {
        Route::get('message/history', 'App\Http\Controllers\Main\CustomerController@getChatHistory');
        Route::post('message/send', 'App\Http\Controllers\Main\CustomerController@sendMessage');
        Route::post('feedback/send', 'App\Http\Controllers\Main\CustomerController@sendFeedback');
    });

    Route::group(['prefix' => 'staff', 'middleware' => 'role'], function () {
        Route::get('customers', 'App\Http\Controllers\Main\StaffController@getCustomers');
        Route::get('customers/deleted', 'App\Http\Controllers\Main\StaffController@getCustomersDeleted');
        Route::get('message/history', 'App\Http\Controllers\Main\StaffController@getChatHistory');
        Route::post('message/staff/send', 'App\Http\Controllers\Main\StaffController@sendMessageToStaff');
        Route::post('message/customer/send', 'App\Http\Controllers\Main\StaffController@sendMessageToCustomer');
        Route::post('customer/delete', 'App\Http\Controllers\Main\StaffController@deleteCustomer');
    });
});