<?php

use App\Http\Controllers\AdminCountroller;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'landingpage'])->name('landingpage');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

// admin
Route::get('/Dashboard', [AdminCountroller::class, 'adminadminpage'])->name('adminadminpage');