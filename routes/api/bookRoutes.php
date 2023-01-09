<?php

use API\BookController as BookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Books API  Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Books API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/books/top-books', 'API\BookController@topBooks')->name('books.top-books');
Route::apiResource('books', BookController::class);
