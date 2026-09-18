<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::controller(CategoryController::class)
    ->prefix('categories')
    ->name('categories.')
    ->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/create', 'showCreateForm')->name('create-form');
        Route::post('/', 'create')->name('create');
        Route::get('/{category}', 'view')->name('view');
        Route::get('/{category}/update', 'showUpdateForm')->name('update-form');
        Route::post('/{category}', 'update')->name('update');
        Route::delete('/{category}', 'delete')->name('delete');
        Route::get('/{category}/products', 'viewProducts')->name('view-products');

        Route::prefix('/{category}/products')
            ->group(function () {
                Route::get('/add', 'showAddProductsForm')->name('add-products-form');
                Route::post('/add', 'addProduct')->name('add-product');
            });
    });

Route::controller(ProductController::class)
    ->prefix('products')
    ->name('products.')
    ->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/create', 'showCreateForm')->name('create-form');
        Route::post('/', 'create')->name('create');
        Route::get('/{product}', 'view')->name('view');
        Route::get('/{product}/update', 'showUpdateForm')->name('update-form');
        Route::post('/{product}', 'update')->name('update');
        Route::delete('/{product}', 'delete')->name('delete');

        Route::prefix('/{product}/shops')
            ->group(function () {
                Route::get('/', 'viewShops')->name('view-shops');
                Route::post('/', 'addShop')->name('add-shop');
                Route::get('/add', 'showAddShopsForm')->name('add-shops-form');
                Route::post('/remove', 'removeShop')->name('remove-shop');
            });
    });

Route::controller(ShopController::class)
    ->prefix('shops')
    ->name('shops.')
    ->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/create', 'showCreateForm')->name('create-form');
        Route::post('/', 'create')->name('create');
        Route::get('/{shop}', 'view')->name('view');
        Route::get('/{shop}/update', 'showUpdateForm')->name('update-form');
        Route::post('/{shop}', 'update')->name('update');
        Route::delete('/{shop}', 'delete')->name('delete');
        Route::get('/{shop}/products', 'viewProducts')->name('view-products');

        Route::prefix('/{shop}/products')
            ->group(function () {
                Route::get('/add', 'showAddProductsForm')->name('add-products-form');
                Route::post('/add', 'addProduct')->name('add-product');
                Route::post('/remove', 'removeProduct')->name('remove-product');
            });
    });