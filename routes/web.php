<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TutorMatchController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MuridDashboardController;

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
});

// ====================== TUTOR ROUTES ======================
Route::middleware(['auth', 'tutor'])
    ->prefix('tutor')
    ->name('tutor.')
    ->group(function () {
        Route::get('/dashboard',           [TutorController::class, 'dashboard'])->name('dashboard');
        Route::get('/profil',              [TutorController::class, 'editProfil'])->name('profil');
        Route::put('/profil',              [TutorController::class, 'simpanProfil'])->name('profil.simpan');
        Route::get('/jadwal',              [TutorController::class, 'jadwal'])->name('jadwal');
        Route::post('/jadwal',             [TutorController::class, 'simpanJadwal'])->name('jadwal.simpan');
        Route::delete('/jadwal/{schedule}',[TutorController::class, 'hapusJadwal'])->name('jadwal.hapus');
        Route::patch('/booking/{booking}/{aksi}', [TutorController::class, 'konfirmasiBooking'])->name('booking.aksi');
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
});

require __DIR__.'/auth.php';
