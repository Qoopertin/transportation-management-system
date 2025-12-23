<?php

use App\Http\Controllers\Api\DriverLocationController;
use App\Http\Controllers\Api\LoadDocumentController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->middleware(['api'])->group(function () {
    
    // Driver location tracking (requires auth, role:driver, rate limited)
    Route::post('/driver/location', [DriverLocationController::class, 'update']);
    
    // Document upload (requires auth)
    Route::post('/loads/{load}/documents', [LoadDocumentController::class, 'store']);
    
});
