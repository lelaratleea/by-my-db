<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::controller(ProductController::class)
    ->prefix('products')
    ->name('products.')
    ->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/create', 'showCreateForm')->name('create-form');
        Route::post('/', 'create')->name('create');
        Route::get('/{product}', 'view')->name('view');
        Route::get('/{product}/update', 'showUpdateForm')->name('update-form');
        Route::put('/{product}', 'update')->name('update');
        Route::delete('/{product}', 'delete')->name('delete');

        // Step 8: เพิ่ม route นี้ หลัง route delete
        Route::get('/{product}/shops', 'viewShops')->name('view-shops');
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
        Route::put('/{shop}', 'update')->name('update');
        Route::delete('/{shop}', 'delete')->name('delete');
    });