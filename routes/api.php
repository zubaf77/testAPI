<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\AuthController;

Route::post('/requests', [RequestController::class, 'store']);
Route::middleware('auth:api')->get('/requests', [RequestController::class, 'index']);
Route::middleware('auth:api')->put('/requests/{id}', [RequestController::class, 'update']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
