<?php

use App\Http\Controllers\API\MidtransCallbackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post(
    '/midtrans/callback',
    [MidtransCallbackController::class, 'callback']
)->name('api.midtrans.callback');