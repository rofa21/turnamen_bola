<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminExportController;
use App\Http\Controllers\Admin\AdminOperatorController;
use App\Http\Controllers\Admin\AdminPrintController;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminTeamController;
use App\Http\Controllers\Admin\AdminVerificationController;
use App\Http\Controllers\Operator\OperatorAuthController;
use App\Http\Controllers\Operator\OperatorDashboardController;
use App\Http\Controllers\Operator\OperatorPlayerController;
use App\Http\Controllers\Operator\OperatorPrintController;
use App\Http\Controllers\Operator\OperatorTeamController;
use App\Http\Middleware\EnsureOperator;
use App\Http\Middleware\EnsureSuperAdmin;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PortalController;

// Public Portal Landing Page for Disdikpora Grassroot Kebumen
Route::get('/', [PortalController::class, 'index'])->name('portal');

/*
|--------------------------------------------------------------------------
| Operator SSB Routes
|--------------------------------------------------------------------------
*/
Route::prefix('operator')->name('operator.')->group(function () {
    Route::get('/login', [OperatorAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [OperatorAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [OperatorAuthController::class, 'logout'])->name('logout');

    Route::middleware(EnsureOperator::class)->group(function () {
        Route::get('/dashboard', [OperatorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/datapemain', [OperatorPlayerController::class, 'index'])->name('datapemain');
        Route::post('/datapemain', [OperatorPlayerController::class, 'store'])->name('datapemain.store');
        Route::put('/datapemain/{player}', [OperatorPlayerController::class, 'update'])->name('datapemain.update');
        Route::delete('/datapemain/{player}', [OperatorPlayerController::class, 'destroy'])->name('datapemain.destroy');
        Route::get('/profile', [OperatorTeamController::class, 'showProfile'])->name('profile');
        Route::post('/profile', [OperatorTeamController::class, 'updateProfile'])->name('profile.update');
    });
});

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware(EnsureSuperAdmin::class)->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Operator management
        Route::resource('operators', AdminOperatorController::class)->except(['create', 'edit', 'show']);

        // Data Tim & Verifikasi Pemain
        Route::get('/teams', [AdminTeamController::class, 'index'])->name('teams.index');
        Route::get('/teams/{team}', [AdminTeamController::class, 'show'])->name('teams.show');
        Route::post('/verify/{player}', [AdminVerificationController::class, 'verify'])->name('verify.player');
        Route::post('/verify-auto-all', [AdminVerificationController::class, 'autoVerifyAll'])->name('verify.auto_all');

        // Schedule management
        Route::resource('schedule', AdminScheduleController::class)->except(['create', 'edit', 'show']);

        // Print documents (Buku Tim & Kartu Pemain)
        Route::get('/print', [AdminPrintController::class, 'index'])->name('print.index');

        // Export data PDF & Excel
        Route::get('/export', [AdminExportController::class, 'index'])->name('export.index');
        Route::get('/export/excel', [AdminExportController::class, 'exportExcel'])->name('export.excel');

        // Settings & Database Backup
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/event', [AdminSettingsController::class, 'updateEvent'])->name('settings.event');
        Route::post('/settings/category', [AdminSettingsController::class, 'storeCategory'])->name('settings.category');
        Route::put('/settings/category/{category}', [AdminSettingsController::class, 'updateCategory'])->name('settings.category.update');
        Route::delete('/settings/category/{category}', [AdminSettingsController::class, 'destroyCategory'])->name('settings.category.destroy');
        Route::get('/settings/backup', [AdminSettingsController::class, 'downloadBackup'])->name('settings.backup');
        Route::post('/settings/restore', [AdminSettingsController::class, 'restoreBackup'])->name('settings.restore');
    });
});
