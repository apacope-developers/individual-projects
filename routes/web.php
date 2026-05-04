<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->is_admin ? redirect('/admin') : redirect('/dashboard');
    }
    return view('landing');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Doctor Registration
Route::get('/doctors/register', function() {
    return view('doctors-register');
})->name('doctors.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard/contacts/add', [DashboardController::class, 'addContact'])->name('dashboard.add-contact');
Route::delete('/dashboard/contacts/{id}', [DashboardController::class, 'deleteContact'])->name('dashboard.delete-contact');
Route::get('/first-aid-guide/{id}', [DashboardController::class, 'getGuideDetails'])->name('first-aid-guide.details');
Route::get('/admin', [AdminController::class, 'index'])->name('admin');
Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');
Route::post('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.delete-user.post');

// User Management Routes
Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.store-user');
Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.update-user');

// Admin CRUD Routes
Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
    Route::get('/admin/body-map', [AdminController::class, 'bodyMapIndex'])->name('admin.body-map');
    Route::post('/admin/body-map/condition', [AdminController::class, 'storeBodyMapCondition'])->name('admin.store-body-map-condition');
    
    Route::get('/admin/symptom-checker', [AdminController::class, 'symptomCheckerIndex'])->name('admin.symptom-checker');
    Route::post('/admin/symptom-checker/option', [AdminController::class, 'storeSymptomCheckerOption'])->name('admin.store-symptom-option');
    
    Route::get('/admin/first-aid-guide', [AdminController::class, 'firstAidGuideIndex'])->name('admin.first-aid-guide');
    Route::post('/admin/first-aid-guide/condition', [AdminController::class, 'storeFirstAidCondition'])->name('admin.store-first-aid-condition');
    
    Route::get('/admin/kit-inventory', [AdminController::class, 'kitInventoryIndex'])->name('admin.kit-inventory');
    Route::post('/admin/kit-inventory/item', [AdminController::class, 'storeKitItem'])->name('admin.store-kit-item');
    
    Route::get('/admin/contacts', [AdminController::class, 'contactsIndex'])->name('admin.contacts');
    Route::post('/admin/contacts', [AdminController::class, 'storeContact'])->name('admin.store-contact');
    Route::put('/admin/contacts/{id}', [AdminController::class, 'updateContact'])->name('admin.update-contact');
    Route::delete('/admin/contacts/{id}', [AdminController::class, 'deleteContact'])->name('admin.delete-contact');

    // Doctor Management
    Route::get('/admin/doctors', [AdminController::class, 'doctorsIndex'])->name('admin.doctors');
    Route::post('/admin/doctors', [AdminController::class, 'storeDoctor'])->name('admin.store-doctor');
    Route::put('/admin/doctors/{doctor}', [AdminController::class, 'updateDoctor'])->name('admin.update-doctor');
    Route::delete('/admin/doctors/{doctor}', [AdminController::class, 'deleteDoctor'])->name('admin.delete-doctor');
    Route::post('/admin/doctors/{doctor}/verify', [AdminController::class, 'verifyDoctor'])->name('admin.verify-doctor');
    Route::post('/admin/doctors/{doctor}/toggle-availability', [AdminController::class, 'toggleDoctorAvailability'])->name('admin.toggle-doctor-availability');
    
    // Test route for force delete
    Route::get('/force-delete/{id}', [AdminController::class, 'forceDelete'])->name('admin.force-delete');
});
