<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
  Route::post('/login', [UserController::class, 'login']);
  Route::post('/logout', [UserController::class, 'logout']);

  Route::middleware('guest')->group(function () {
      Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
      Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
  });
});

Route::middleware('auth:sanctum')->group(function () {
  Route::apiResource('events', EventController::class);
});