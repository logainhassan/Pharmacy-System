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
Route::post('/login', 'API\UserController@login');
Route::post('/register', 'API\UserController@store');
Route::put('/users/{user}', 'API\UserController@update');

Route::group([], function()
{
    Route::get('/clients/{client}/addresses', 'API\AddressController@index');
    Route::post('/clients/{client}/addresses', 'API\AddressController@store');
    Route::get('/addresses/{address}', 'API\AddressController@show');
    Route::put('/addresses/{address}', 'API\AddressController@update');
    Route::delete('/addresses/{address}', 'API\AddressController@destroy');
});

Route::get('/clients/{client}/orders', 'API\OrderController@index');
Route::get('/orders/{order}', 'API\OrderController@show');