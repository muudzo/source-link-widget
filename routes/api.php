<?php

use App\Http\Controllers\LinkController;
use App\Http\Controllers\LinkCategoryController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('public/links', [LinkController::class, 'index']);
Route::get('public/categories', [LinkCategoryController::class, 'index']);
Route::get('public/categories/{category}', [LinkCategoryController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Links
    Route::apiResource('links', LinkController::class);
    
    // Categories
    Route::apiResource('categories', LinkCategoryController::class);
});