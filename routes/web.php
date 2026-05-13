<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TutorMatchController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MuridDashboardController;
use App\Http\Controllers\Tutor\LaporanSesiController;
use App\Http\Controllers\Admin\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================== ROOT REDIRECT ======================
Route::get('/', function () {
    /** @var \Illuminate\Auth\AuthManager $auth */
    $auth = auth();

    if ($auth->check()) {
        /** @var \App\Models\User $user */
        $user = $auth->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isTutor()) {
            return redirect()->route('tutor.dashboard');
        }
        return redirect()->route('murid.dashboard');
    }

    return redirect()->route('login');
})->name('home');

// ====================== MURID ROUTES ======================
Route::middleware(['auth', 'murid'])
    ->prefix('murid')
    ->name('murid.')
    ->group(function () {

        Route::get('/dashboard', [MuridDashboardController::class, 'index'])->name('dashboard');

        Route::get('/cari-tutor', [TutorMatchController::class, 'index'])->name('cari-tutor');
        Route::get('/tutor/{tutor}', [TutorMatchController::class, 'show'])->name('detail-tutor');

        Route::get('/booking/{tutor}', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/booking/{tutor}', [BookingController::class, 'store'])->name('booking.store');

        Route::get('/riwayat', [BookingController::class, 'riwayat'])->name('riwayat');

        // --- Tambahan untuk navbar ---
        // Sementara redirect ke riwayat sampai halaman booking murid dibuat
        Route::get('/booking', fn() => redirect()->route('murid.riwayat'))->name('booking');
    });

// ====================== TUTOR ROUTES ======================
Route::middleware(['auth', 'tutor'])
    ->prefix('tutor')
    ->name('tutor.')
    ->group(function () {
        Route::get('/dashboard',            [TutorController::class, 'dashboard'])->name('dashboard');
        Route::get('/profil',               [TutorController::class, 'editProfil'])->name('profil');
        Route::put('/profil',               [TutorController::class, 'simpanProfil'])->name('profil.simpan');
        Route::get('/jadwal',               [TutorController::class, 'jadwal'])->name('jadwal');
        Route::post('/jadwal',              [TutorController::class, 'simpanJadwal'])->name('jadwal.simpan');
        Route::delete('/jadwal/{schedule}', [TutorController::class, 'hapusJadwal'])->name('jadwal.hapus');
        Route::patch('/booking/{booking}/{aksi}', [TutorController::class, 'konfirmasiBooking'])->name('booking.aksi');

        // --- Tambahan untuk navbar ---
        // Sementara redirect ke dashboard sampai halaman dibuat
        Route::get('/booking',  fn() => redirect()->route('tutor.dashboard'))->name('booking');
        Route::get('/laporan',  fn() => redirect()->route('tutor.dashboard'))->name('laporan');
    });
// nabela

// === ROUTE TUTOR ===
Route::prefix('daftar-tutor')->name('tutor.')->group(function () {
    Route::get('/', [TutorController::class, 'index'])->name('index');
    Route::get('/create', [TutorController::class, 'create'])->name('create');
    Route::post('/', [TutorController::class, 'store'])->name('store');
    Route::get('/{tutor}', [TutorController::class, 'show'])->name('show');
});

// ====================== ADMIN ROUTES ======================
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard',                [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/tutor',                    [AdminController::class, 'daftarTutor'])->name('tutor');
        Route::patch('/tutor/{profile}/{aksi}', [AdminController::class, 'verifikasiTutor'])->name('verifikasi');
        Route::get('/matching-log',             [AdminController::class, 'matchingLog'])->name('matching-log');

        // --- Tambahan untuk navbar ---
        // Semua sementara redirect ke dashboard sampai halaman masing-masing dibuat
        Route::get('/member',   fn() => redirect()->route('admin.dashboard'))->name('member');
        Route::get('/booking',  fn() => redirect()->route('admin.dashboard'))->name('booking');
        Route::get('/pks',      fn() => redirect()->route('admin.dashboard'))->name('pks');

        // Keuangan
        Route::prefix('keuangan')->name('keuangan.')->group(function () {
            Route::get('/dashboard',  fn() => redirect()->route('admin.dashboard'))->name('dashboard');
            Route::get('/pembayaran', fn() => redirect()->route('admin.dashboard'))->name('pembayaran');
            Route::get('/gaji-tutor', fn() => redirect()->route('admin.dashboard'))->name('gaji-tutor');
            Route::get('/invoice',    fn() => redirect()->route('admin.dashboard'))->name('invoice');
            Route::get('/rekap',      fn() => redirect()->route('admin.dashboard'))->name('rekap');
        });
    });

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

require __DIR__.'/auth.php';
