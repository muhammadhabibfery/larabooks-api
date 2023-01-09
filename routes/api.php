<?php

use API\Auth\LogoutController;
use API\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Kavist\RajaOngkir\Facades\RajaOngkir;

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

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('/logout', LogoutController::class);
    });

Route::post('/register', RegisterController::class);
Route::post('/login', 'API\Auth\LoginController@login');

$files = glob(__DIR__ . "/api/*.php");
foreach ($files as  $file) {
    require($file);
}
