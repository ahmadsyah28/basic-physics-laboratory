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
use App\Http\Controllers\Admin\AdminVisitController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminVisiMisiController;
use App\Http\Controllers\Admin\AdminEquipmentController;
use App\Http\Controllers\Admin\AdminPeminjamanController;

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
    Route::get('/staff/{staff}/edit', [AdminStaffController::class, 'edit'])->name('staff.edit');

    Route::prefix('equipment')->name('equipment.')->group(function () {
    Route::get('/', [AdminEquipmentController::class, 'index'])->name('index');
    Route::get('/create', [AdminEquipmentController::class, 'create'])->name('create');
    Route::post('/', [AdminEquipmentController::class, 'store'])->name('store');
    Route::get('/{equipment}', [AdminEquipmentController::class, 'show'])->name('show');
    Route::get('/{equipment}/edit', [AdminEquipmentController::class, 'edit'])->name('edit');
    Route::put('/{equipment}', [AdminEquipmentController::class, 'update'])->name('update');
    Route::delete('/{equipment}', [AdminEquipmentController::class, 'destroy'])->name('destroy');
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

    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
    Route::get('/', [AdminPeminjamanController::class, 'index'])->name('index');
    Route::get('/{peminjaman}', [AdminPeminjamanController::class, 'show'])->name('show');
    Route::put('/{peminjaman}/status', [AdminPeminjamanController::class, 'updateStatus'])->name('update-status');
    Route::delete('/{peminjaman}', [AdminPeminjamanController::class, 'destroy'])->name('destroy');
    Route::get('/export/csv', [AdminPeminjamanController::class, 'export'])->name('export');
    Route::post('/bulk-update', [AdminPeminjamanController::class, 'bulkUpdateStatus'])->name('bulk-update');
    Route::get('/dashboard/data', [AdminPeminjamanController::class, 'getDashboardData'])->name('dashboard-data');
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
