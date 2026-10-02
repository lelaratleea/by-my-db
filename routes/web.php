<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// ทุก route ห้าม browser cache (กันปัญหากด Back แล้วเห็นหน้าเก่าหลัง logout)
Route::middleware(['cache.headers:no_store;no_cache;must_revalidate;max_age=0'])->group(static function (): void {
    // ---------- Authentication ----------
    Route::controller(LoginController::class)
        ->prefix('/auth')
        ->group(static function (): void {
            // ต้องชื่อ login เพราะ Laravel จะ redirect ผู้ที่ยังไม่ login มาที่ route นี้
            Route::get('/login', 'showForm')->name('login');
            Route::post('/login', 'authenticate')->name('authentication');
            Route::match(['get', 'post'], '/logout', 'logout')->name('logout');
        });

    // ---------- ต้อง login ก่อนถึงเข้าได้ ----------
    Route::middleware(['auth'])->group(static function (): void {
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

                Route::prefix('/{category}/products')->group(function () {
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

                Route::prefix('/{product}/shops')->group(function () {
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

                Route::prefix('/{shop}/products')->group(function () {
                    Route::get('/add', 'showAddProductsForm')->name('add-products-form');
                    Route::post('/add', 'addProduct')->name('add-product');
                    Route::post('/remove', 'removeProduct')->name('remove-product');
                });
            });

        Route::controller(UserController::class)
            ->prefix('users')
            ->name('users.')
            ->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('/create', 'showCreateForm')->name('create-form');
                Route::post('/', 'create')->name('create');

                // ต้องอยู่ก่อน /{user} ไม่งั้น "self" จะถูกตีความเป็น email
                Route::prefix('/self')
                    ->name('selves.')
                    ->group(function () {
                        Route::get('/', 'viewSelf')->name('view');
                        Route::get('/update', 'showUpdateSelfForm')->name('update-form');
                        Route::post('/', 'updateSelf')->name('update');
                    });

                Route::get('/{user}', 'view')->name('view');
                Route::get('/{user}/update', 'showUpdateForm')->name('update-form');
                Route::post('/{user}', 'update')->name('update');
                Route::post('/{user}/delete', 'delete')->name('delete');
            });
    });

    Route::redirect('/', '/products');
});
