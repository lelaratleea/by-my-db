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
    ->group(static function(Router $router): void {
        $prefixName = \array_last($router->getGroupStack())['as'];

        Route::any('', static fn() => redirect()
            ->route("{$prefixName}index"))
            ->name('index');

        Route::get('', 'index')->name('list');
        Route::get('/{product}', 'view')->name('view');
    });

Route::controller(ShopController::class)
    ->prefix('/shops')
    ->name('shops.')
    ->group(static function(Router $router): void {
        $prefixName = \array_last($router->getGroupStack())['as'];

        Route::any('', static fn() => redirect()
            ->route("{$prefixName}index"))
            ->name('index');

        Route::get('', 'index')->name('list');
        Route::get('/{shop}', 'view')->name('view');
    });