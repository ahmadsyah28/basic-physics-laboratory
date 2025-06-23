<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\FacilitiesController;
use App\Http\Controllers\EquipmentLoanController;
use App\Http\Controllers\TestingServicesController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\VisitSchedulingController;

/*
|--------------------------------------------------------------------------
| Main Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/equipment', [HomeController::class, 'equipment'])->name('equipment');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

/*
|--------------------------------------------------------------------------
| Staff & Facilities Routes
|--------------------------------------------------------------------------
*/

Route::get('/staff', [StaffController::class, 'index'])->name('staff');
Route::get('/facilities', [FacilitiesController::class, 'index'])->name('facilities');

/*
|--------------------------------------------------------------------------
| Articles & Gallery Routes (Simple Names)
|--------------------------------------------------------------------------
*/

// Articles
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show')->where('id', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
/*
|--------------------------------------------------------------------------
| Services Routes (Existing - No Changes)
|--------------------------------------------------------------------------
*/

// Services - Equipment Loan
Route::prefix('services/equipment-loan')->name('equipment.')->group(function () {
    Route::get('/', [EquipmentLoanController::class, 'index'])->name('loan');
    Route::get('/{id}', [EquipmentLoanController::class, 'show'])->name('detail');
    Route::post('/{id}/request', [EquipmentLoanController::class, 'requestLoan'])->name('request');
});

// Services - Testing Services
Route::prefix('services/testing')->name('testing.')->group(function () {
    Route::get('/', [TestingServicesController::class, 'index'])->name('services');
    Route::get('/{id}', [TestingServicesController::class, 'show'])->name('detail');
    Route::post('/{id}/request', [TestingServicesController::class, 'requestTest'])->name('request');
});

// Services - Visit Scheduling
Route::prefix('services/visit-scheduling')->name('visit.')->group(function () {
    Route::get('/', [VisitSchedulingController::class, 'index'])->name('index');
    Route::post('/schedule', [VisitSchedulingController::class, 'store'])->name('store');
    Route::get('/available-slots', [VisitSchedulingController::class, 'getAvailableSlots'])->name('available-slots');
});
