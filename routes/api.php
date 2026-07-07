<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// Route publik: bisa diakses tanpa token
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Grouping: semua route di dalam grup ini wajib menyertakan token (auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    // except(['destroy']): route hapus dipisah agar bisa diberi proteksi role:admin
    Route::apiResource('categories', CategoryController::class)->except(['destroy']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])
        ->middleware('role:admin');

    Route::apiResource('books', BookController::class)->except(['destroy']);
    Route::delete('books/{book}', [BookController::class, 'destroy'])
        ->middleware('role:admin');

    Route::apiResource('borrows', BorrowController::class)->except(['destroy']);
    Route::delete('borrows/{borrow}', [BorrowController::class, 'destroy'])
        ->middleware('role:admin');
});
