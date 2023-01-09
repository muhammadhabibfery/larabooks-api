<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Checkout API  Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Checkout API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/couriers', 'API\CheckoutController@getAllCouriers');
        Route::get('/my-order', 'API\CheckoutController@myOrder');
        Route::post('/checkout', 'API\CheckoutController@shipping');
        Route::post('/checkout/process', 'API\CheckoutController@process');
        Route::post('/checkout/submit', 'API\CheckoutController@submit');
    });

Route::get('/provincies', 'API\CheckoutController@getAllProvincies');
Route::get('/cities', 'API\CheckoutController@getAllCities');
Route::post('/payment/notification', 'API\CheckoutController@notificationHandler');
