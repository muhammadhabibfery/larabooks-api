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
    // public function home(){
    //     if(checkRole(['CUSTOMER'], ))
    // }


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
                        ->parameters(['users' => 'user'])
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
            Route::get('/categories/trash', 'CategoryController@trash')->name('categories.trash');
            Route::get('/categories/restore/{slug}', 'CategoryController@restore')->name('categories.restore');
            Route::delete('/categories/force-delete/{slug}', 'CategoryController@forceDelete')->name('categories.force-delete');
            Route::get('/categories/edit/{category:slug}', 'CategoryController@edit')->name('categories.edit');

            Route::resource('categories', 'CategoryController')
                ->parameters(['categories' => 'category:slug'])
                ->except(['edit']);
            // Route::middleware('authRole:ADMIN,STAFF')
            //     ->group(function () {
            //     });
        });

        Route::macro('bookRoutes', function () {
            Route::get('/books/trash', 'BookController@trash')->name('books.trash');
            Route::get('/books/restore/{slug}', 'BookController@restore')->name('books.restore');
            Route::delete('/books/force-delete/{slug}', 'BookController@forceDelete')->name('books.force-delete');
            Route::get('/books/edit/{book:slug}', 'BookController@edit')->name('books.edit');

            Route::resource('books', 'BookController')
                ->parameters(['books' => 'book:slug'])
                ->except(['edit']);
            // Route::middleware('authRole:ADMIN,STAFF')
            //     ->group(function () {
            //     });
        });

        Route::macro('orderRoutes', function () {
            Route::get('/orders/trash', 'OrderController@trash')->name('orders.trash');
            Route::get('/orders/restore/{invoice_number}', 'OrderController@restore')->name('orders.restore');
            Route::delete('/orders/force-delete/{invoice_number}', 'OrderController@forceDelete')->name('orders.force-delete');
            Route::get('/orders/edit/{order:invoice_number}', 'OrderController@edit')->name('orders.edit');

            Route::resource('orders', 'OrderController')
                ->parameters(['orders' => 'order:invoice_number'])
                ->except(['edit']);
            // Route::middleware('authRole:ADMIN,STAFF')
            //     ->group(function () {
            //     });
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
