<?php

use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// http://127.0.0.1:8000/api/register
Route::post('/register', [ClientController::class, 'store'])->name('register');

// http://127.0.0.1:8000/api/login
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    // http://127.0.0.1:8000/api/users
    Route::get('/users', [ClientController::class, 'index'])->name('users');

    // CARRO
    // http://127.0.0.1:8000/api/register_car
    Route::post('/register_car', [CarController::class, 'store'])->name('register_car');

    // http://127.0.0.1:8000/api/cars
    Route::get('/cars', [CarController::class, 'index'])->name('cars');
});
