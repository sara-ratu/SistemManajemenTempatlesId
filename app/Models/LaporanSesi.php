<?php
// ═══════════════════════════════════════════════════
// Tambahkan ke routes/web.php
// ═══════════════════════════════════════════════════

use App\Http\Controllers\Tutor\LaporanSesiController;
use App\Http\Controllers\Admin\LaporanController;

// ── Tutor: Laporan Sesi ────────────────────────────
Route::middleware(['auth', 'tutor'])->prefix('tutor')->name('tutor.')->group(function () {
    Route::get('/laporan', [LaporanSesiController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/booking/{booking}', [LaporanSesiController::class, 'create'])->name('laporan.create');
    Route::post('/laporan/booking/{booking}', [LaporanSesiController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{laporanSesi}', [LaporanSesiController::class, 'show'])->name('laporan.show');
});

// ── Admin: Laporan Sesi ────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{laporanSesi}', [LaporanController::class, 'show'])->name('laporan.show');
    Route::patch('/laporan/{laporanSesi}/approve', [LaporanController::class, 'approve'])->name('laporan.approve');
});
