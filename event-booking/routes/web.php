<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReceiptController;
require __DIR__.'/auth.php';
/*
|--------------------------------------------------------------------------
| Public (LANDING PAGE)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| Auth Routes (LOGIN / REGISTER MUST BE OUTSIDE)
|--------------------------------------------------------------------------
*/




/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // EVENTS
    Route::resource('events', EventController::class);

    // BOOKINGS
    Route::post('/events/{event}/book', [BookingController::class, 'store'])
        ->name('bookings.store');

    Route::get('/my-bookings', [BookingController::class, 'myBookings'])
        ->name('bookings.my');

    // PAYMENTS
    Route::get('/payments/{booking}', [PaymentController::class, 'show'])
        ->name('payments.show');

    Route::post('/payment/process', [PaymentController::class, 'process'])
        ->name('payment.process');

    Route::get('/payment/mtn/{booking}', [PaymentController::class, 'mtn'])
        ->name('payment.mtn');

    Route::get('/payment/airtel/{booking}', [PaymentController::class, 'airtel'])
        ->name('payment.airtel');

    Route::get('/payment/momo/{booking}', [PaymentController::class, 'momo'])
        ->name('payment.momo');

    Route::get('/payment/bank/{booking}', [PaymentController::class, 'bank'])
        ->name('payment.bank');

    Route::post('/payment/confirm/{booking}', [PaymentController::class, 'confirm'])
        ->name('payment.confirm');

    Route::get('/payment/confirmation/{booking}', [PaymentController::class, 'confirmation'])
        ->name('payment.confirmation');

    // RECEIPT
    Route::get('/receipt/{id}/download', [ReceiptController::class, 'download'])
        ->name('receipt.download');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/bookings', [AdminController::class, 'index'])->name('admin.bookings');
});


/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect()->route('events.index');
})->middleware('auth')->name('dashboard');