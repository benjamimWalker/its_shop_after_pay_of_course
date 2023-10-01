<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('users', UserController::class)->middleware('auth:sanctum');
Route::delete('users', [UserController::class, 'destroyAll'])->middleware('auth:sanctum');

Route::resource('stores', StoreController::class)->middleware('auth:sanctum');
Route::delete('stores', [StoreController::class, 'destroyAll'])->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::post('signup', [AuthController::class, 'signup'])->name('auth.signup');
