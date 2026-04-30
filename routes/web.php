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
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/admin', [AdminController::class, 'index'])->name('admin');
Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');

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
    
    // Test route for force delete
    Route::get('/force-delete/{id}', [AdminController::class, 'forceDelete'])->name('admin.force-delete');
});
