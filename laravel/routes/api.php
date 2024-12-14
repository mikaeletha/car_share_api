<?php

use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//USER
Route::post('/register', [ClientController::class, 'store'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');

// CAR
Route::get('/cars', [CarController::class, 'index'])->name('list_available_cars');
Route::post('/register_car', [CarController::class, 'store'])->name('register_car');
Route::get('/cars/{id}', [CarController::class, 'show'])->name('list_owner_s_cars');



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/users', [ClientController::class, 'index'])->name('users');
});
