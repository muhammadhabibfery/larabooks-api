<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';


    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::macro('userRoutes', function () {
            Route::middleware('authRole:ADMIN')
                ->group(function () {
                    Route::get('/users/trash', 'UserController@indexTrash')->name('users.trash');
                    Route::get('/users/trash/restore/{user}', 'UserController@restore')->name('users.trash.restore');
                    Route::get('/users/trash/{user}', 'UserController@showTrash')->name('users.trash.show');
                    Route::delete('/users/trash/force-delete/{user}', 'UserController@forceDelete')->name('users.trash.force-delete');
                    Route::get('/users/edit/{user}', 'UserController@edit')->name('users.edit');

                    Route::resource('users', 'UserController')
                        ->except(['edit']);
                });
        });

        Route::macro('profileRoutes', function () {
            Route::get('/profile/password/edit', 'ProfileController@editPassword')->name('profiles.password.edit');
            Route::patch('/profile/password/{user}', 'ProfileController@updatePassword')->name('profiles.password.update');
            Route::get('/profile/edit', 'ProfileController@editProfile')->name('profiles.edit');
            Route::patch('/profile/{user}', 'ProfileController@updateProfile')->name('profiles.update');
        });

        Route::macro('categoryRoutes', function () {
            Route::get('/categories/trash', 'CategoryController@indexTrash')->name('categories.trash');
            Route::get('/categories/trash/restore/{category}', 'CategoryController@restore')->name('categories.trash.restore');
            Route::get('/categories/trash/{category}', 'CategoryController@showTrash')->name('categories.trash.show');
            Route::delete('/categories/trash/force-delete/{category}', 'CategoryController@forceDelete')
                ->name('categories.trash.force-delete');
            Route::get('/categories/edit/{category}', 'CategoryController@edit')->name('categories.edit');

            Route::resource('categories', 'CategoryController')
                ->except(['edit']);
        });

        Route::macro('bookRoutes', function () {
            Route::get('/books/trash', 'BookController@indexTrash')->name('books.trash');
            Route::get('/books/trash/restore/{book}', 'BookController@restore')->name('books.trash.restore');
            Route::get('/books/trash/{book}', 'BookController@showTrash')->name('books.trash.show');
            Route::delete('/books/trash/force-delete/{book}', 'BookController@forceDelete')
                ->name('books.trash.force-delete');
            Route::get('/books/edit/{book}', 'BookController@edit')->name('books.edit');

            Route::resource('books', 'BookController')
                ->except(['edit']);
        });

        Route::macro('orderRoutes', function () {
            Route::get('/orders/trash', 'OrderController@indexTrash')->name('orders.trash');
            Route::get('/orders/trash/restore/{order}', 'OrderController@restore')->name('orders.trash.restore');
            Route::get('/orders/trash/{order}', 'OrderController@showTrash')->name('orders.trash.show');
            Route::delete('/orders/trash/force-delete/{order}', 'OrderController@forceDelete')
                ->name('orders.trash.force-delete');

            Route::resource('orders', 'OrderController')
                ->except(['edit', 'update']);
        });

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
