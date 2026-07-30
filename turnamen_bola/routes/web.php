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
use App\Http\Controllers\Operator\OperatorTeamController;
use App\Http\Controllers\PortalController;
use App\Http\Middleware\EnsureOperator;
use App\Http\Middleware\EnsureSuperAdmin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

// PORTAL UTAMA PUBLIK
Route::get('/', [PortalController::class, 'index'])->name('portal');

// ============================================================
// SERVING STORAGE FILES DIRECTLY (Bulletproof Multi-Path Search)
// ============================================================
Route::get('/storage/{path}', function ($path) {
    $candidates = [
        storage_path('app/public/' . $path),
        storage_path('app/' . $path),
        storage_path($path),
        base_path('public/storage/' . $path),
    ];

    $foundPath = null;
    foreach ($candidates as $candidate) {
        if (File::exists($candidate) && ! File::isDirectory($candidate)) {
            $foundPath = $candidate;
            break;
        }
    }

    if (! $foundPath) {
        abort(404);
    }

    $mimeType = File::mimeType($foundPath) ?: 'image/jpeg';
    return response()->file($foundPath, ['Content-Type' => $mimeType]);
})->where('path', '.*')->name('storage.local');

/*
|--------------------------------------------------------------------------
| LOGIN RAHASIA ADMIN (PANITIA PUSAT)
|--------------------------------------------------------------------------
*/
Route::prefix('panitia-pusat-kebumen')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
});

// ROUTE INTERNAL ADMIN (DIPROTEKSI MIDDLEWARE)
Route::middleware(['web', EnsureSuperAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('operators', AdminOperatorController::class)->except(['create', 'edit', 'show']);

    Route::get('/teams', [AdminTeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/{team}', [AdminTeamController::class, 'show'])->name('teams.show');
    Route::post('/verify/{player}', [AdminVerificationController::class, 'verify'])->name('verify.player');
    Route::post('/verify-auto-all', [AdminVerificationController::class, 'autoVerifyAll'])->name('verify.auto_all');

    Route::resource('schedule', AdminScheduleController::class)->except(['create', 'edit', 'show']);

    Route::get('/print', [AdminPrintController::class, 'index'])->name('print.index');
    Route::get('/export', [AdminExportController::class, 'index'])->name('export.index');
    Route::get('/export/excel', [AdminExportController::class, 'exportExcel'])->name('export.excel');

    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/event', [AdminSettingsController::class, 'updateEvent'])->name('settings.event');
    Route::post('/settings/category', [AdminSettingsController::class, 'storeCategory'])->name('settings.category');
    Route::put('/settings/category/{category}', [AdminSettingsController::class, 'updateCategory'])->name('settings.category.update');
    Route::delete('/settings/category/{category}', [AdminSettingsController::class, 'destroyCategory'])->name('settings.category.destroy');
    Route::get('/settings/backup', [AdminSettingsController::class, 'downloadBackup'])->name('settings.backup');
    Route::post('/settings/restore', [AdminSettingsController::class, 'restoreBackup'])->name('settings.restore');
});

/*
|--------------------------------------------------------------------------
| LOGIN RAHASIA OPERATOR (SSB)
|--------------------------------------------------------------------------
*/
Route::prefix('portal-ssb-kebumen')->group(function () {
    Route::get('/login', [OperatorAuthController::class, 'showLoginForm'])->name('operator.login');
    Route::post('/login', [OperatorAuthController::class, 'login'])->name('operator.login.submit');
});

// ROUTE INTERNAL OPERATOR (DIPROTEKSI MIDDLEWARE)
Route::middleware(['web', EnsureOperator::class])->prefix('operator')->name('operator.')->group(function () {
    Route::post('/logout', [OperatorAuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [OperatorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/datapemain', [OperatorPlayerController::class, 'index'])->name('datapemain');
    Route::post('/datapemain', [OperatorPlayerController::class, 'store'])->name('datapemain.store');
    Route::put('/datapemain/{player}', [OperatorPlayerController::class, 'update'])->name('datapemain.update');
    Route::delete('/datapemain/{player}', [OperatorPlayerController::class, 'destroy'])->name('datapemain.destroy');
    Route::get('/profile', [OperatorTeamController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [OperatorTeamController::class, 'updateProfile'])->name('profile.update');
});
