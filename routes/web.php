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
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AdminEquipmentLoanController;
use App\Http\Controllers\Admin\AdminTestingServicesController;
use App\Http\Controllers\Admin\AdminVisitController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminVisiMisiController;
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
| Services Routes
|--------------------------------------------------------------------------
*/
// Services - Equipment Loan
Route::prefix('services/equipment-loan')->name('equipment.')->group(function () {
    Route::get('/', [EquipmentLoanController::class, 'index'])->name('loan');
    Route::get('/{id}', [EquipmentLoanController::class, 'show'])->name('detail');
    Route::post('/request', [EquipmentLoanController::class, 'requestLoan'])->name('request');
    Route::get('/availability/check', [EquipmentLoanController::class, 'checkAvailability'])->name('check-availability');
    Route::get('/history', [EquipmentLoanController::class, 'getLoanHistory'])->name('history');
});
// Services - Testing Services
Route::prefix('services/testing')->name('testing.')->group(function () {
    Route::get('/', [TestingServicesController::class, 'index'])->name('services');
    Route::get('/{id}', [TestingServicesController::class, 'show'])->name('detail');
    Route::post('/request', [TestingServicesController::class, 'requestTest'])->name('request');
    Route::get('/schedule-availability', [TestingServicesController::class, 'getScheduleAvailability'])->name('schedule-availability');
});
// Services - Visit Scheduling
Route::prefix('services/visit-scheduling')->name('visit.')->group(function () {
    Route::get('/', [VisitSchedulingController::class, 'index'])->name('index');
    Route::post('/schedule', [VisitSchedulingController::class, 'store'])->name('store');
    Route::get('/available-slots', [VisitSchedulingController::class, 'getAvailableSlots'])->name('available-slots');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['web', 'auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Staff Management
    Route::resource('staff', AdminStaffController::class);
    // Di dalam group admin
    Route::get('/staff/{staff}/edit', [AdminStaffController::class, 'edit'])->name('staff.edit');

    // Equipment Management
    Route::prefix('equipment')->name('equipment.')->group(function () {
        Route::get('/', [AdminEquipmentLoanController::class, 'equipmentIndex'])->name('index');
        Route::get('/create', [AdminEquipmentLoanController::class, 'equipmentCreate'])->name('create');
        Route::post('/', [AdminEquipmentLoanController::class, 'equipmentStore'])->name('store');
        Route::get('/{alat}/edit', [AdminEquipmentLoanController::class, 'equipmentEdit'])->name('edit');
        Route::put('/{alat}', [AdminEquipmentLoanController::class, 'equipmentUpdate'])->name('update');
        Route::delete('/{alat}', [AdminEquipmentLoanController::class, 'equipmentDestroy'])->name('destroy');
    });

    // Equipment Loan Management
    Route::prefix('equipment-loan')->name('equipment-loan.')->group(function () {
        Route::get('/', [AdminEquipmentLoanController::class, 'index'])->name('index');
        Route::get('/{peminjaman}', [AdminEquipmentLoanController::class, 'show'])->name('show');
        Route::put('/{peminjaman}/status', [AdminEquipmentLoanController::class, 'updateStatus'])->name('update-status');
    });

    // Testing Types Management
    Route::prefix('testing-types')->name('testing-types.')->group(function () {
        Route::get('/', [AdminTestingServicesController::class, 'testingTypesIndex'])->name('index');
        Route::get('/create', [AdminTestingServicesController::class, 'testingTypesCreate'])->name('create');
        Route::post('/', [AdminTestingServicesController::class, 'testingTypesStore'])->name('store');
        Route::get('/{jenisPengujian}/edit', [AdminTestingServicesController::class, 'testingTypesEdit'])->name('edit');
        Route::put('/{jenisPengujian}', [AdminTestingServicesController::class, 'testingTypesUpdate'])->name('update');
        Route::delete('/{jenisPengujian}', [AdminTestingServicesController::class, 'testingTypesDestroy'])->name('destroy');
    });

    // Testing Services Management
    Route::prefix('testing')->name('testing.')->group(function () {
        Route::get('/', [AdminTestingServicesController::class, 'index'])->name('index');
        Route::get('/{pengujian}', [AdminTestingServicesController::class, 'show'])->name('show');
        Route::put('/{pengujian}/status', [AdminTestingServicesController::class, 'updateStatus'])->name('update-status');
        Route::post('/{pengujian}/upload-results', [AdminTestingServicesController::class, 'uploadResults'])->name('upload-results');
    });

    // Visit Scheduling Management
    Route::prefix('visits')->name('visits.')->group(function () {
        Route::get('/', [AdminVisitController::class, 'index'])->name('index');
        Route::get('/create', [AdminVisitController::class, 'create'])->name('create');
        Route::post('/', [AdminVisitController::class, 'store'])->name('store');
        Route::get('/{kunjungan}', [AdminVisitController::class, 'show'])->name('show');
        Route::get('/{kunjungan}/edit', [AdminVisitController::class, 'edit'])->name('edit');
        Route::put('/{kunjungan}', [AdminVisitController::class, 'update'])->name('update');
        Route::put('/{kunjungan}/status', [AdminVisitController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{kunjungan}', [AdminVisitController::class, 'destroy'])->name('destroy');
        Route::get('/calendar/view', [AdminVisitController::class, 'calendar'])->name('calendar');
        Route::get('/calendar/data', [AdminVisitController::class, 'getCalendarData'])->name('calendar.data');
    });

    // Article Management
    Route::resource('articles', AdminArticleController::class);
    Route::delete('/articles/image/{gambar}', [AdminArticleController::class, 'destroyImage'])->name('articles.image.destroy');

    // User Management (Admin users)
    Route::middleware([\App\Http\Middleware\SuperAdminMiddleware::class])->group(function () {
        Route::resource('users', AdminUserController::class);
    });

    // Vision & Mission Management
    Route::prefix('visimisi')->name('visimisi.')->group(function () {
        Route::get('/', [AdminVisiMisiController::class, 'index'])->name('index');
        Route::put('/profil', [AdminVisiMisiController::class, 'updateProfil'])->name('update-profil');
        Route::post('/misi', [AdminVisiMisiController::class, 'storeMisi'])->name('store-misi');
        Route::put('/misi/{misi}', [AdminVisiMisiController::class, 'updateMisi'])->name('update-misi');
        Route::delete('/misi/{misi}', [AdminVisiMisiController::class, 'destroyMisi'])->name('destroy-misi');
    });
});
