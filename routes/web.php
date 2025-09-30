<?php

use App\Http\Controllers\LinkController;
use App\Http\Controllers\LinkCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Link Categories
Route::get('/categories', [LinkCategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [LinkCategoryController::class, 'create'])->name('categories.create');
Route::post('/categories', [LinkCategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{category}/edit', [LinkCategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{category}', [LinkCategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}', [LinkCategoryController::class, 'destroy'])->name('categories.destroy');

// API Routes for browser extension
Route::prefix('api')->group(function () {
    // Categories API
    Route::get('/categories', [LinkCategoryController::class, 'index']);
    Route::post('/categories', [LinkCategoryController::class, 'store']);
    Route::get('/categories/{category}', [LinkCategoryController::class, 'show']);
    Route::put('/categories/{category}', [LinkCategoryController::class, 'update']);
    Route::delete('/categories/{category}', [LinkCategoryController::class, 'destroy']);
    
    // Links API
    Route::get('/links', [LinkController::class, 'apiIndex']);
    Route::post('/links', [LinkController::class, 'apiStore']);
    Route::get('/links/{link}', [LinkController::class, 'apiShow']);
    Route::put('/links/{link}', [LinkController::class, 'apiUpdate']);
    Route::delete('/links/{link}', [LinkController::class, 'apiDestroy']);
});

// Web Routes
Route::get('/links', [LinkController::class, 'index'])->name('links.index');
Route::get('/links/create', [LinkController::class, 'create'])->name('links.create');
Route::post('/links', [LinkController::class, 'store'])->name('links.store');
Route::get('/links/{link}', [LinkController::class, 'show'])->name('links.show');
Route::get('/links/{link}/edit', [LinkController::class, 'edit'])->name('links.edit');
Route::put('/links/{link}', [LinkController::class, 'update'])->name('links.update');
Route::delete('/links/{link}', [LinkController::class, 'destroy'])->name('links.destroy');