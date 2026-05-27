<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckUserActive;
use App\Http\Middleware\ManagerMiddleware;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');

// CATEGORY CONTROLLER
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('', [CategoryController::class, 'index'])->name('index');
    Route::get('create', [CategoryController::class, 'create'])
        ->name('create')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::post('', [CategoryController::class, 'store'])
        ->name('store')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::get('{category}', [CategoryController::class, 'show'])->name('show');
    Route::get('{category}/products', [CategoryController::class, 'categoryProducts'])->name('category.products');
    Route::get('{category}/edit', [CategoryController::class, 'edit'])
        ->name('edit')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::put('{category}', [CategoryController::class, 'update'])
        ->name('update')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::delete('{category}', [CategoryController::class, 'destroy'])
        ->name('destroy')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::put('{category}/restore', [CategoryController::class, 'restore'])
        ->name('restore')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::delete('{category}/forceDestroy', [CategoryController::class, 'forceDestroy'])
        ->name('forceDestroy')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::get('trashed', [CategoryController::class, 'trashed'])
        ->name('trashed')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
});

//BRAND CONTROLLER
Route::prefix('brands')->name('brands.')->group(function () {
    Route::get('', [BrandController::class, 'index'])->name('index');
    Route::get('create', [BrandController::class, 'create'])
        ->name('create')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::post('', [BrandController::class, 'store'])
        ->name('store')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::get('{brand}', [BrandController::class, 'show'])->name('show');
    Route::get('{brand}/edit', [BrandController::class, 'edit'])
        ->name('edit')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::put('{brand}', [BrandController::class, 'update'])
        ->name('update')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::delete('{brand}', [BrandController::class, 'destroy'])
        ->name('destroy')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::put('{brand}/restore', [BrandController::class, 'restore'])
        ->name('restore')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::delete('{brand}/forceDestroy', [BrandController::class, 'forceDestroy'])
        ->name('forceDestroy')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::get('trashed', [BrandController::class, 'trashed'])
        ->name('trashed')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
});

//COUNTRY CONTROLLER
Route::prefix('countries')->name('countries.')->group(function () {
    Route::get('', [CountryController::class, 'index'])->name('index');
    Route::get('create', [CountryController::class, 'create'])
        ->name('create')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::post('', [CountryController::class, 'store'])
        ->name('store')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::get('{country}', [CountryController::class, 'show'])->name('show');
    Route::get('{country}/edit', [CountryController::class, 'edit'])
        ->name('edit')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::put('{country}', [CountryController::class, 'update'])
        ->name('update')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::delete('{country}', [CountryController::class, 'destroy'])
        ->name('destroy')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::put('{country}/restore', [CountryController::class, 'restore'])
        ->name('restore')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::delete('{country}/forceDestroy', [CountryController::class, 'forceDestroy'])
        ->name('forceDestroy')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::get('trashed', [CountryController::class, 'trashed'])
        ->name('trashed')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

//PRODUCT CONTROLLER
Route::prefix('products')->name('products.')->group(function () {
    Route::get('', [ProductController::class, 'index'])->name('index');
    Route::get('create', [ProductController::class, 'create'])
        ->name('create')
        ->middleware(ManagerMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::post('', [ProductController::class, 'store'])
        ->name('store')
        ->middleware(ManagerMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::get('{product}', [ProductController::class, 'show'])->name('show');
    Route::get('{product}/edit', [ProductController::class, 'edit'])
        ->name('edit')
        ->middleware(ManagerMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::put('{product}', [ProductController::class, 'update'])
        ->name('update')
        ->middleware(ManagerMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::delete('{product}', [ProductController::class, 'destroy'])
        ->name('destroy')
        ->middleware(ManagerMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::put('{product}/restore', [ProductController::class, 'restore'])
        ->name('restore')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::delete('{product}/forceDestroy', [ProductController::class, 'forceDestroy'])
        ->name('forceDestroy')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
    Route::get('trashed', [ProductController::class, 'trashed'])
        ->name('trashed')
        ->middleware(AdminMiddleware::class)
        ->middleware(CheckUserActive::class);
});

//USER CONTROLLER
Route::prefix('admin/users')->name('admin.users.')->group(function () {
    Route::get('', [UserController::class, 'index'])
        ->name('index')
        ->middleware(AdminMiddleware::class);

    Route::put('{id}/toggleActive', [UserController::class, 'toggleActive'])
        ->name('toggleActive')
        ->middleware(AdminMiddleware::class);

    Route::put('{id}/changeRole', [UserController::class, 'changeRole'])
        ->name('changeRole')
        ->middleware(AdminMiddleware::class);
});

