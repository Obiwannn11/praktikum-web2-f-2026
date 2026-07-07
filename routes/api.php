<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// apiResource: sekali daftar langsung membuat 5 route CRUD (index, store, show, update, destroy)
Route::apiResource('categories', CategoryController::class);
Route::apiResource('books', BookController::class);
Route::apiResource('borrows', BorrowController::class);
