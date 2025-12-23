<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DriverMapController;
use App\Http\Controllers\LoadController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Loads
    Route::resource('loads', LoadController::class);
    Route::post('/loads/{load}/assign-driver', [LoadController::class, 'assignDriver'])->name('loads.assign-driver');
    Route::post('/loads/{load}/update-status', [LoadController::class, 'updateStatus'])->name('loads.update-status');
    
    // Driver Map (for admin/dispatcher)
    Route::get('/drivers/map', [DriverMapController::class, 'index'])->name('drivers.map');
    
    // Driver Interface
    Route::get('/driver', [DriverController::class, 'index'])->name('driver.index');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
