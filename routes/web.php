<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\MatchController;
use App\Http\Controllers\MemberDashboardController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\HonorController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PksController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ReviewModerationController;

use App\Http\Controllers\Tutor\ScheduleController;
use App\Http\Controllers\Tutor\TutorAreaController;
use App\Http\Controllers\Tutor\LaporanSesiController;
use App\Http\Controllers\Tutor\PendapatanController;
use App\Http\Controllers\Tutor\TutorController;

use App\Http\Controllers\Member\BookingController as MemberBookingController;
use App\Http\Controllers\Member\PembayaranController;
use App\Http\Controllers\Member\ReviewController;

use App\Http\Controllers\Chat\ChatRoomController;
use App\Http\Controllers\Chat\ChatMessageController;

// Root
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();
    if ($user->isAdmin()) return redirect()->route('admin.dashboard');
    if ($user->isTutor()) return redirect()->route('tutor.dashboard');
    return redirect()->route('Member.dashboard');
})->name('home');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Daftar Tutor Publik
Route::prefix('daftar-tutor')->name('tutor.')->group(function () {
    Route::get('/', [TutorController::class, 'index'])->name('index');
    Route::get('/create', [TutorController::class, 'create'])->name('create');
    Route::post('/', [TutorController::class, 'store'])->name('store');
    Route::get('/{tutor}', [TutorController::class, 'show'])->name('show');
});

// MEMBER
Route::middleware(['auth', 'Member'])
    ->prefix('Member')
    ->name('Member.')
    ->group(function () {

    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    Route::get('/cari-tutor', [MatchController::class, 'index'])->name('cari-tutor');
    Route::post('/cari-tutor', [MatchController::class, 'search'])->name('cari-tutor.search');
    Route::get('/tutor/{tutor}', [MatchController::class, 'show'])->name('detail-tutor');

    Route::resource('booking', MemberBookingController::class)->except(['edit', 'update', 'destroy']);
    Route::patch('/booking/{booking}/cancel', [MemberBookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/riwayat', [MemberBookingController::class, 'riwayat'])->name('riwayat');

    Route::get('/pembayaran/{booking}/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran/{booking}', [PembayaranController::class, 'store'])->name('pembayaran.store');

    Route::get('/review/{booking}/create', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/review/{booking}', [ReviewController::class, 'store'])->name('review.store');

    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatRoomController::class, 'index'])->name('index');
        Route::get('/{chatRoom}', [ChatRoomController::class, 'show'])->name('show');
        Route::post('/{chatRoom}/pesan', [ChatMessageController::class, 'store'])->name('pesan');
    });
});

// TUTOR
Route::middleware(['auth', 'tutor'])
    ->prefix('tutor')
    ->name('tutor.')
    ->group(function () {

    Route::get('/dashboard', [TutorController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [TutorController::class, 'editProfil'])->name('profil');
    Route::put('/profil', [TutorController::class, 'simpanProfil'])->name('profil.simpan');

    Route::resource('jadwal', ScheduleController::class)->except(['show', 'edit', 'update']);
    Route::resource('area-mengajar', TutorAreaController::class)->except(['show', 'edit', 'update']);

    // === Jadwal ===
    Route::resource('jadwal', ScheduleController::class)
         ->except(['show', 'edit', 'update']);

    Route::get('/laporan', [LaporanSesiController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/booking/{booking}', [LaporanSesiController::class, 'create'])->name('laporan.create');
    Route::post('/laporan/booking/{booking}', [LaporanSesiController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{laporanSesi}', [LaporanSesiController::class, 'show'])->name('laporan.show');

    Route::get('/pendapatan', [PendapatanController::class, 'index'])->name('pendapatan');

    Route::get('/booking', [TutorController::class, 'bookingMasuk'])->name('booking');
    Route::patch('/booking/{booking}/{aksi}', [TutorController::class, 'konfirmasiBooking'])->name('booking.aksi');

    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatRoomController::class, 'index'])->name('index');
        Route::get('/{chatRoom}', [ChatRoomController::class, 'show'])->name('show');
        Route::post('/{chatRoom}/pesan', [ChatMessageController::class, 'store'])->name('pesan.store');
    });
});

// ADMIN
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/', [AdminController::class, 'daftarMember'])->name('index');
        Route::get('/{user}/edit', [AdminController::class, 'memberEdit'])->name('edit');
        Route::put('/{user}', [AdminController::class, 'memberUpdate'])->name('update');
        Route::patch('/{user}/toggle-status', [AdminController::class, 'memberToggleStatus'])->name('toggle-status');
    });

    Route::resource('tutor', App\Http\Controllers\Admin\TutorController::class);

    Route::get('/verifikasi', [VerificationController::class, 'index'])->name('verifikasi');
    Route::get('/verifikasi/tutor/{profile}', [VerificationController::class, 'showTutor'])->name('verifikasi.show');
    Route::patch('/verifikasi/tutor/{profile}/approve', [VerificationController::class, 'approveTutor'])->name('verifikasi.approve');
    Route::patch('/verifikasi/tutor/{profile}/reject', [VerificationController::class, 'rejectTutor'])->name('verifikasi.reject');

    Route::get('/matching-log', [AdminController::class, 'matchingLog'])->name('matching-log');
    Route::get('/booking', [AdminController::class, 'daftarBooking'])->name('booking');
    Route::patch('/booking/{booking}/confirm', [AdminController::class, 'confirmBooking'])->name('booking.confirm');

    Route::resource('pks', PksController::class)->except(['edit', 'update', 'destroy']);

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{laporanSesi}', [LaporanController::class, 'show'])->name('laporan.show');
    Route::patch('/laporan/{laporanSesi}/approve', [LaporanController::class, 'approve'])->name('laporan.approve');

    Route::prefix('honor')->name('honor.')->group(function () {
        Route::get('/', [HonorController::class, 'index'])->name('index');
        Route::patch('{honor}/transfer', [HonorController::class, 'transfer'])->name('transfer');
    });

    Route::get('/pembayaran', [PaymentController::class, 'index'])->name('pembayaran');
    Route::get('/pembayaran/{pembayaran}', [PaymentController::class, 'show'])->name('pembayaran.show');
    Route::patch('/pembayaran/{pembayaran}/verifikasi', [PaymentController::class, 'verifikasi'])->name('pembayaran.verifikasi');

    Route::get('/review', [ReviewModerationController::class, 'index'])->name('review');
    Route::patch('/review/{review}/approve', [ReviewModerationController::class, 'approve'])->name('review.approve');
    Route::patch('/review/{review}/reject', [ReviewModerationController::class, 'reject'])->name('review.reject');

    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/dashboard', [HonorController::class, 'dashboard'])->name('dashboard');
        Route::get('/pembayaran', [PaymentController::class, 'rekap'])->name('pembayaran');
        Route::get('/gaji-tutor', [HonorController::class, 'gajiTutor'])->name('gaji-tutor');
        Route::get('/invoice', [PaymentController::class, 'invoice'])->name('invoice');
        Route::get('/rekap', [HonorController::class, 'rekap'])->name('rekap');
    });
});

require __DIR__.'/auth.php';
