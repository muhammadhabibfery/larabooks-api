<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => ['prevent-back']], function () {
    Auth::routes(['register' => false]);
    Route::get('/home', fn () => 'Home page for customer')->name('home');

    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'authRole:ADMIN,STAFF']], function () {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
        Route::userRoutes();
        Route::categoryRoutes();
        Route::bookRoutes();
        Route::orderRoutes();
    });

    Route::profileRoutes();
});
