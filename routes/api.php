<?php

use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CountryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//    return $request->user();
// })->middleware('auth:sanctum');

// API/BRAND CONTROLLER
Route::prefix('brands')->group(function () {
    Route::get('trashed', [BrandController::class, 'trashed']);
    Route::get('', [BrandController::class, 'index']);
    Route::post('', [BrandController::class, 'store']);
    Route::get('{id}',[BrandController::class,'show']);
    Route::put('',[BrandController::class,'update']);
    Route::put('{id}',[BrandController::class,'update']);
    Route::patch('',[BrandController::class,'update']);
    Route::patch('{id}',[BrandController::class,'update']);
    Route::delete('', [BrandController::class, 'destroy']);
    Route::delete('{id}',[BrandController::class, 'destroy']);
    Route::patch('{id}/restore',[BrandController::class,'restore']);
    Route::delete('{id}/forceDestroy', [BrandController::class, 'forceDestroy']);
});

// API/COUNTRY CONTROLLER
Route::prefix('countries')->group(function () {
    Route::get('trashed', [CountryController::class, 'trashed']);
    Route::get('', [CountryController::class, 'index']);
    Route::post('', [CountryController::class, 'store']);
    Route::get('{id}',[CountryController::class,'show']);
    Route::put('',[CountryController::class,'update']);
    Route::put('{id}',[CountryController::class,'update']);
    Route::patch('',[CountryController::class,'update']);
    Route::patch('{id}',[CountryController::class,'update']);
    Route::delete('', [CountryController::class, 'destroy']);
    Route::delete('{id}',[CountryController::class, 'destroy']);
    Route::patch('{id}/restore',[CountryController::class,'restore']);
    Route::delete('{id}/forceDestroy', [CountryController::class, 'forceDestroy']);
});

// API/CATEGORY CONTROLLER
Route::prefix('categories')->group (function () {
    Route::get('trashed', [CategoryController::class, 'trashed']);
    Route::get('', [CategoryController::class, 'index']);
    Route::post('', [CategoryController::class, 'store']);
    Route::get('{id}',[CategoryController::class,'show']);
    Route::put('',[CategoryController::class,'update']);
    Route::put('{id}',[CategoryController::class,'update']);
    Route::patch('',[CategoryController::class,'update']);
    Route::patch('{id}',[CategoryController::class,'update']);
    Route::delete('', [CategoryController::class, 'destroy']);
    Route::delete('{id}',[CategoryController::class, 'destroy']);
    Route::patch('{id}/restore',[CategoryController::class,'restore']);
    Route::delete('{id}/forceDestroy', [CategoryController::class, 'forceDestroy']);
});

// API/PRODUCT CONTROLLER
Route::prefix('products')->group (function () {
    Route::get('trashed', [ProductController::class, 'trashed']);
    Route::get('', [ProductController::class, 'index']);
    Route::post('', [ProductController::class, 'store']);
    Route::get('{id}',[ProductController::class,'show']);
    Route::put('',[ProductController::class,'update']);
    Route::put('{id}',[ProductController::class,'update']);
    Route::patch('',[ProductController::class,'update']);
    Route::patch('{id}',[ProductController::class,'update']);
    Route::delete('', [ProductController::class, 'destroy']);
    Route::delete('{id}',[ProductController::class, 'destroy']);
    Route::patch('{id}/restore',[ProductController::class,'restore']);
    Route::delete('{id}/forceDestroy', [ProductController::class, 'forceDestroy']);
});
