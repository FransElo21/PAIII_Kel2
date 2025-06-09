<?php

use App\Http\Controllers\FacilityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\MidtransCallbackController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// ===============================
// PUBLIC ROUTES (ACCESSIBLE WITHOUT LOGIN)
// ===============================

// Landing page
Route::middleware(['check_banned'])->group(function () {
Route::get('/', [AuthController::class, 'landingpage'])->name('landingpage');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login1', [AuthController::class, 'login'])->name('login1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email verification routes
Route::get('/verify-email/{userId}/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');
Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('email.resend');

// Registration routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register-insert', [AuthController::class, 'insertRegister'])->name('insertRegister');

// Property details
Route::get('/detail-property/{id}', [UserController::class, 'showDetailProperty'])->name('detail-property.show');


// ===============================
// CUSTOMER ROUTES (REQUIRES AUTHENTICATION)
// ===============================

// Homestay and Kost properties
Route::get('/homestay-properties', [UserController::class, 'homestayProperty'])->name('homestay.properties');
Route::get('/kost-properties', [UserController::class, 'kostProperty'])->name('kost.properties');

// Booking requests
Route::middleware(['auth'])->group(function () {
    Route::get('/sewa/ajukan/{id}', [UserController::class, 'ajukanSewa'])->name('sewa.ajukan');
});

// Property search
Route::get('/search-property-homestay', [UserController::class, 'search_homestay'])->name('search_homestay');
Route::get('/search-property-kost', [UserController::class, 'search_kost'])->name('search_kost');
Route::get('/cari-property', [UserController::class, 'search_welcomeProperty'])->name('search_welcomeProperty');

// ===============================
// BOOKING ROUTES (REQUIRES AUTHENTICATION)
// ===============================

Route::middleware(['auth', 'check_banned', 'check_role:3'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profileuser.show');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profileuser.edit');
    Route::post('/profile/update', [UserController::class, 'update'])->name('profileuser.update');

    // Riwayat transaksi
    Route::get('/riwayat-transaksi', [UserController::class, 'riwayat_transaksi'])->name('riwayat-transaksi.index');

    Route::get('/riwayat-transaksi/{booking_id}', [UserController::class, 'detail_transaksi'])->name('riwayat-transaksi.detail');

    // Pemesanan (Booking)
    Route::post('/pemesanan', [UserController::class, 'pemesanan'])->name('pemesanan.index');

    // Route GET untuk menampilkan halaman Konfirmasi (menarik data dari session)
    Route::get('/booking-confirm', [UserController::class, 'show_confirm'])
        ->name('booking.confirm.show');

    // Route POST yang memproses data Isi Data Diri → simpan di session → load konfirmasi
    Route::post('/booking-confirm', [UserController::class, 'confirm_booking'])
        ->name('booking.confirm.post');

    // Route POST untuk benar-benar menyimpan booking (dari halaman Konfirmasi)
    Route::post('/pemesanan-store', [UserController::class, 'store_bokings'])
        ->name('booking.store');

    Route::get('/pemesanan-kost', [BookingController::class, 'showKostBooking'])->name('booking.kost');
    Route::post('/store-bokingkost', [BookingController::class, 'store_bokingkost'])->name('store_bokingkost');

    // Pembayaran (Payment)
    Route::get('/pembayaran/sukses/{booking_id}', [UserController::class, 'payment_success'])->name('booking.success');
});


// ===============================
// REVIEW & NOTIFICATION ROUTES
// ===============================

// Review routes
Route::get('/review/create', [ReviewController::class, 'create'])->name('review.create');
Route::post('/review/store', [ReviewController::class, 'store'])->name('review.store');

// Notification routes
Route::get('/notifications', [NotificationController::class, 'getReviewNotifications']);

// ===============================
// ADDITIONAL ROUTES
// ===============================

// About page
Route::get('/tentang', [UserController::class, 'tentang'])->name('tentang');

// Owner's transaction history
Route::get('/pemilik/riwayat-transaksi', [OwnerController::class, 'riwayat_transaksi'])->name('pemilik.riwayat-transaksi');

// Payment routes
Route::get('/booking/{booking_id}/payment', [PaymentController::class, 'payment_show'])->name('payment.show');
// Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
Route::get('/payment/success/{booking_id}', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/notification', [PaymentController::class, 'notification']);

Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
Route::post('/payment/cancel/{booking_id}', [PaymentController::class, 'cancel'])->name('payment.cancel');


Route::get('/pemilik/ulasan', [ReviewController::class, 'index'])->name('pemilik.ulasan');


Route::get('/admin/pengusaha/unconfirmed', [AdminController::class, 'getUnconfirmed'])->name('admin.pengusaha.unconfirmed');
Route::post('/pengusaha/confirm', [AdminController::class, 'confirmPengusaha'])->name('admin.pengusaha.confirm');




// ===============================
// ADMIN ROUTES (REQUIRES ADMIN ROLE)
// ===============================

Route::get('/dashboard-admin', [AdminController::class, 'showAdminpage'])->name('showAdminpage');
Route::get('/users/role2', [AdminController::class, 'showUsersRole2Pengusaha'])->name('users.rolePengusaha');
Route::get('/users/role3', [AdminController::class, 'showUsersRole3Penyewa'])->name('users.rolePenyewa');



// ===============================
// OWNER ROUTES (REQUIRES OWNER ROLE)
// ===============================
Route::middleware(['auth', 'check_banned', 'check_role:2'])->group(function () {
    Route::get('/dashboard-owner', [OwnerController::class, 'showOwnerpage'])->name('owner.dashboard');
    Route::get('/property', [OwnerController::class, 'showPropertypage'])->name('owner.property');
    Route::get('/add-property', [OwnerController::class, 'add_property'])->name('owner.add-property');
    Route::post('/store-property', [OwnerController::class, 'store_property'])->name('owner.store-property');
    Route::post('/delete-property', [OwnerController::class, 'delete_property'])->name('owner.delete-property');
    Route::get('/property/edit/{propertyId}', [OwnerController::class, 'edit_property'])->name('owner.edit-property');
    Route::put('/property/update/{id}', [OwnerController::class, 'update_property'])->name('owner.update-property');

    // Facility & Image Management
    Route::post('/add-facility', [OwnerController::class, 'addFacility'])->name('property.addFacility');
    Route::get('/get-facilities', [OwnerController::class, 'getFacilities'])->name('get.facilities');
    Route::post('/add-image', [OwnerController::class, 'addImage'])->name('property.image.add');
    Route::delete('/property/image/delete', [OwnerController::class, 'deleteImage'])->name('property.image.delete');
    Route::delete('/property/facility/delete', [OwnerController::class, 'deleteFacility'])->name('property.facility.delete');

    // Room Management
    Route::post('/property/room/add', [OwnerController::class, 'addRoom'])->name('property.room.add');   
    Route::get('/property/room/{id}/edit', [OwnerController::class, 'editRoom'])->name('property.room.edit');
    Route::put('/property/room/update', [OwnerController::class, 'updateRoom'])->name('property.room.update');
    Route::delete('property/room/{room_id}',[OwnerController::class, 'deleteRoom'])->name('property.room.delete');

    // Location Routes
    Route::get('/provinces', [LocationController::class, 'getProvinces']);
    Route::get('/cities/{province_id}', [LocationController::class, 'getCities']);
    Route::get('/districts/{cities_id}', [LocationController::class, 'getDistricts']);
    Route::get('/subdistricts/{district_id}', [LocationController::class, 'getSubdistricts']);


    Route::get('/property/{property_id}/selected-facilities', [OwnerController::class, 'get_FacilitiesByPropertyId'])->name('property.get-selected-facilities');

});

Route::get('/booking-owner-detail/{id}', [OwnerController::class, 'detail_transaksi_owner'])->name('booking-owner.detail');


// Route::prefix('api')->middleware('api')->group(function () {
//     Route::post('/midtrans/callback', [MidtransCallbackController::class, 'callback']);
// });

// Route::post('/midtrans/callback', [MidtransCallbackController::class, 'callback']);

Route::get('/owner/bookings/{booking_id}', [OwnerController::class, 'detail_booking_owner'])
     ->name('owner.bookings.detail')
     ->middleware('auth');

Route::post('/owner/reviews/report', [ReviewController::class, 'report'])->name('owner.reviews.report');



Route::get('/admin/pengusaha/detail', [AdminController::class, 'getDetailPengusaha'])->name('admin.pengusaha.detail');
Route::get('/admin/penyewa/detail', [AdminController::class, 'getDetailPenyewa'])->name('admin.penyewa.detail');
Route::post('/admin/akun/ban', [AdminController::class, 'ban_akun'])->name('admin.ban.akun');
Route::post('admin/unban-akun', [AdminController::class, 'unban_akun'])->name('admin.unban.akun');

// Halaman Homestay
Route::get('/admin/homestay', [AdminController::class, 'homestayProperty_admin'])->name('admin.homestay');
// Halaman Kost
Route::get('/admin/kost', [AdminController::class, 'kostProperty_admin'])->name('admin.kost');
// Detail Property
Route::get('/admin/property/{id}', [AdminController::class, 'showDetail'])->name('admin.property.detail');

Route::prefix('admin/tipe-property')->name('admin.tipe_property.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::post('/store', [AdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('edit');
    Route::put('/{id}/update', [AdminController::class, 'update'])->name('update');
    Route::delete('/{id}/destroy', [AdminController::class, 'destroy'])->name('destroy');
});

Route::prefix('admin/')->name('admin.')->group(function () {
    Route::get('facilities', [FacilityController::class, 'index'])->name('facilities.index');
    Route::get('facilities/create', [FacilityController::class, 'create'])->name('facilities.create');
    Route::post('facilities', [FacilityController::class, 'store'])->name('facilities.store');
    Route::get('facilities/{id}/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
    Route::put('facilities/{id}', [FacilityController::class, 'update'])->name('facilities.update');
    Route::delete('facilities/{id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
});


// Grup prefix “admin/homestay”
Route::prefix('admin/homestay')->group(function () {
    // Rute AJAX untuk modal detail
    Route::get('{id}/detail-ajax', [AdminController::class, 'detailAjax'])
         ->name('homestay.detailAjax');

    // Rute “biasa” untuk showDetailProperty (misalnya halaman edit/detail full page)
    Route::get('{id}/edit', [AdminController::class, 'showDetailProperty'])
         ->name('homestay.showDetailProperty');
});

Route::get('admin/homestay/{id}/bookings-ajax', [AdminController::class, 'bookingsAjax'])->whereNumber('id')->name('admin.homestay.bookingsAjax');
Route::get('admin/booking/{id}/detail-ajax', [AdminController::class, 'bookingDetailAjax'])
     ->whereNumber('id')
     ->name('admin.booking.detailAjax');

// Route::get('admin/reviews', [AdminController::class, 'allReviews'])->name('admin.reviews.index');

Route::get('reviews', [AdminController::class, 'allReviews'])->name('admin.reviews.index');
Route::patch('admin/reviews/{id}/hide',   [AdminController::class, 'hide'])->name('admin.reviews.hide');
Route::patch('admin/reviews/{id}/unhide', [AdminController::class, 'unhide'])->name('admin.reviews.unhide');



Route::get('/admin/ulasan', [AdminController::class, 'index_ulasan'])->name('admin.ulasan');

// Booking history table
Route::get('/admin/bookings', [BookingController::class, 'index'])->name('admin.bookings.index');

// Booking detail for modal (AJAX)
Route::get('/admin/bookings/{id}/detail', [BookingController::class, 'showDetail'])->name('admin.bookings.detail');

Route::get('/payment/{booking_id}/resi', [PaymentController::class, 'downloadResi'])
    ->name('payment.downloadResi');




Route::get('password/forgot',    [ResetPasswordController::class, 'showForgotForm'])->name('password.forgot');
Route::post('password/forgot',   [ResetPasswordController::class, 'sendToken']);

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset',        [ResetPasswordController::class, 'resetPassword'])->name('password.update');

Route::get('/profile-owner', [OwnerController::class, 'show'])->name('profileowner.show');
Route::get('/profile-owner/edit', [OwnerController::class, 'edit'])->name('profileowner.edit');
Route::post('/profile-owner/update', [OwnerController::class, 'update'])->name('profileowner.update');

Route::get('/admin/profile', [AdminController::class, 'show'])->name('admin.profile');


