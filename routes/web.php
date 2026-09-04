<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::controller(ProductController::class)
    ->prefix('/products')
    ->name('products.')
    ->group(static function (Router $router): void {   // ✅ แก้ตรงนี้
        $prefixName = \array_last($router->getGroupStack())['as'];
        Route::any('', static fn() => redirect()->route("{$prefixName}list"))
            ->name('index');
        Route::get('', 'list')->name('list');
        Route::post('', 'create')->name('create');
        Route::get('/create', 'showCreateForm')->name('create-form');
        Route::prefix('/{product}')
            ->group(static function (): void {
                Route::get('', 'view')->name('view');
                Route::post('', 'update')->name('update');
                Route::get('/update', 'showUpdateForm')->name('update-form');
                Route::post('/delete', 'delete')->name('delete');
            });
    });

Route::controller(ShopController::class)
    ->prefix('/shops')
    ->name('shops.')
    ->group(static function () use ($router): void {
        $prefixName = \array_last($router->getGroupStack())['as'];

        Route::any('', static fn() => redirect()->route("{$prefixName}list"))
            ->name('index');
        Route::get('', 'list')->name('list');
        Route::post('', 'create')->name('create');
        Route::get('/create', 'showCreateForm')->name('create-form');

        Route::prefix('/{shop}')
            ->group(static function (): void {
                Route::get('', 'view')->name('view');
                Route::post('', 'update')->name('update');
                Route::get('/update', 'showUpdateForm')->name('update-form');
                Route::post('/delete', 'delete')->name('delete');
            });
    });