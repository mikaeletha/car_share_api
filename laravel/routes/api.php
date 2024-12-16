<?php

use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CarRentalController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [ClientController::class, 'store'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Protected Routes
// Route::group(['middleware' => ['auth:sanctum']], function () {
Route::get('/cars', [CarController::class, 'index'])->name('list_available_cars');
Route::get('/users', [ClientController::class, 'index'])->name('users');
// Route::post('/car-rentals/borrow', [CarRentalController::class, 'borrow'])->name('borrow');
Route::post('/car-rentals/borrow', [CarRentalController::class, 'borrow'])->name('borrow');
Route::post('/car-rentals/{rental_id}/return', [CarRentalController::class, 'return'])->name('car-rentals.return');
Route::put('/cars/{id}/remove', [CarController::class, 'remove'])->name('remove_car');
Route::get('/cars/owner/{id}', [CarController::class, 'show'])->name('list_owner_s_cars');
// });
