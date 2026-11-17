<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/tokens/read-only', [AuthController::class, 'readOnlyToken']);

    Route::get('/books', [BookController::class, 'index'])->middleware('ability:books:read');
    Route::get('/books/{id}', [BookController::class, 'show'])->middleware('ability:books:read');

    Route::post('/books', [BookController::class, 'store'])->middleware('abilities:books:write');
    Route::put('/books/{id}', [BookController::class, 'update'])->middleware('abilities:books:write');
    Route::delete('/books/{id}', [BookController::class, 'destroy'])->middleware('abilities:books:write');
});