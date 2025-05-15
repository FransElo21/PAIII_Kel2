<?php

use App\Http\Controllers\API\MidtransCallbackController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Route::post('/provinces', [LocationController::class, 'storeProvinces']);
// Route::post('/cities', [LocationController::class, 'storeCities']);
// Route::post('/districts', [LocationController::class, 'storeDistricts']);
// Route::post('/subdistricts', [LocationController::class, 'storeSubdistricts']);

Route::post('/midtrans/callback', [MidtransCallbackController::class, 'callback'])->name('midtrans.callback');
// routes/api.php
Route::post('/midtrans/notification', [PaymentController::class, 'notification']);
