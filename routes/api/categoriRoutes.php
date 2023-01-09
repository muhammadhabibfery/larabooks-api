<?php

use API\CategoryController as CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Categories API  Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Categories API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])
    ->group(function () {
        //
    });

Route::apiResource('categories', CategoryController::class);
