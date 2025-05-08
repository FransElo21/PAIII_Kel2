<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\DB;

Route::get('/', [AuthController::class, 'landingpage'])->name('landingpage');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login1', [AuthController::class, 'login'])->name('login1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Verivikasi Email

// Hapus route closure lama, gunakan controller
Route::get('/verify-email/{userId}/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');

// Resend Verification Email
Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('email.resend');

// Register & Login
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register-insert', [AuthController::class, 'insertRegister'])->name('insertRegister');

Route::get('/detail-property/{id}', [UserController::class, 'showDetailProperty'])->name('detail-property.show');


Route::post('/userType-insert', [AuthController::class, 'insertUserType'])->name('insertUserType');
Route::get('/userRole-insert', [AuthController::class, 'insertUserRole'])->name('insertUserRole');

// admin
Route::get('/dashboard-admin', [AdminController::class, 'showAdminpage'])->name('showAdminpage');


// customer
Route::get('/homestay-properties', [UserController::class, 'homestayProperty'])->name('homestay.properties');
Route::get('/kost-properties', [UserController::class, 'kostProperty'])->name('kost.properties');

Route::middleware(['auth'])->group(function () {
    Route::get('/sewa/ajukan/{id}', [UserController::class, 'ajukanSewa'])->name('sewa.ajukan');
});


Route::get('/search-property', [UserController::class, 'search'])->name('search');




// Halaman form pemesanan
Route::get('/pemesanan', [UserController::class, 'pemesanan'])->name('pemesanan.index');
Route::post('/pemesanan-store', [UserController::class, 'store_bokings'])->name('booking.store');

// Halaman pembayaran
Route::get('/pembayaran/{booking_id}', [UserController::class, 'payment_show'])->name('payment.show');

// Proses pembayaran
Route::post('/pembayaran/proses', [UserController::class, 'payment_process'])->name('payment.process');

// Halaman sukses pembayaran
Route::get('/pembayaran/sukses/{booking_id}', [UserController::class, 'payment_success'])->name('booking.success');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profileuser.show');
});

Route::get('/riwayat-transaksi', [UserController::class, 'riwayat_transaksi'])->name('riwayat-transaksi.index');


// owner
Route::get('/dashboard-owner', [OwnerController::class, 'showOwnerpage'])->name('owner.dashboard');

Route::get('/property', [OwnerController::class, 'showPropertypage'])->name('owner.property');
Route::get('/add-property', [OwnerController::class, 'add_property'])->name('owner.add-property');
Route::post('/store-property', [OwnerController::class, 'store_property'])->name('owner.store-property');
Route::post('/delete-property', [OwnerController::class, 'delete_property'])->name('owner.delete-property');

Route::get('/property/edit/{propertyId}', [OwnerController::class, 'edit_property'])->name('owner.edit-property');

Route::put('/property/update/{id}', [OwnerController::class, 'update_property'])->name('owner.update-property');


Route::get('/provinces', [LocationController::class, 'getProvinces']);
Route::get('/cities/{province_id}', [LocationController::class, 'getCities']);
Route::get('/districts/{cities_id}', [LocationController::class, 'getDistricts']);
Route::get('/subdistricts/{district_id}', [LocationController::class, 'getSubdistricts']);


Route::post('/add-facility', [OwnerController::class, 'addFacility'])->name('property.addFacility');
Route::get('/get-facilities', [OwnerController::class, 'getFacilities'])->name('get.facilities');

Route::post('/add-image', [OwnerController::class, 'addImage'])->name('property.image.add');

Route::delete('/property/image/delete', [OwnerController::class, 'deleteImage'])->name('property.image.delete');
Route::delete('/property/facility/delete', [OwnerController::class, 'deleteFacility'])->name('property.facility.delete');

Route::post('/property/room/add', [OwnerController::class, 'addRoom'])->name('property.room.add');   
Route::get('/property/room/{id}/edit', [OwnerController::class, 'editRoom'])->name('property.room.edit');
Route::put('/property/room/update', [OwnerController::class, 'updateRoom'])->name('property.room.update');
Route::delete('/property/room/delete', [OwnerController::class, 'deleteRoom'])->name('property.room.delete');

// Route::post('/property/room/add', [OwnerController::class, 'addRoom'])->name('property.room.add');

Route::get('/property/{property_id}/selected-facilities', [OwnerController::class, 'get_FacilitiesByPropertyId'])->name('property.get-selected-facilities');

Route::get('/tentang', [UserController::class, 'tentang'])->name('tentang');









