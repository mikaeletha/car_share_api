<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'login']);


// http://127.0.0.1:8000/api/register
Route::post('/register', [ClientController::class, 'store']);
// http://127.0.0.1:8000/api/login
Route::get('/register', [ClientController::class, 'store']);
