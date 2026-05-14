<?php

use Illuminate\Support\Facades\Route;

// ── Controllers ───────────────────────────────────────────────
use App\Http\Controllers\TutorMatchController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\MuridDashboardController;
use App\Http\Controllers\ProfileController;

// Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\HonorController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PksController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ReviewModerationController;

// Tutor
use App\Http\Controllers\Tutor\ScheduleController;
use App\Http\Controllers\Tutor\TutorAreaController;
use App\Http\Controllers\Tutor\LaporanSesiController;
use App\Http\Controllers\Tutor\PendapatanController;

// Member
use App\Http\Controllers\Member\BookingController as MemberBookingController;
use App\Http\Controllers\Member\PembayaranController;
use App\Http\Controllers\Member\ReviewController;

// Chat
use App\Http\Controllers\Chat\ChatRoomController;
use App\Http\Controllers\Chat\ChatMessageController;

// ── Root Redirect ─────────────────────────────────────────────
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    $user = auth()->user();
    if ($user->isAdmin()) return redirect()->route('admin.dashboard');
    if ($user->isTutor()) return redirect()->route('tutor.dashboard');
    return redirect()->route('murid.dashboard');
})->name('home');

// ── Profile (semua role) ──────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Daftar Tutor (publik) ─────────────────────────────────────
Route::prefix('daftar-tutor')->name('tutor.')->group(function () {
    Route::get('/', [TutorController::class, 'index'])->name('index');
    Route::get('/create', [TutorController::class, 'create'])->name('create');
    Route::post('/', [TutorController::class, 'store'])->name('store');
    Route::get('/{tutor}', [TutorController::class, 'show'])->name('show');
});

// ═══════════════════════════════════════════════════════════════
// MURID
// ═══════════════════════════════════════════════════════════════
Route::middleware(['auth', 'murid'])
    ->prefix('murid')
    ->name('murid.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [MuridDashboardController::class, 'index'])->name('dashboard');

    // Cari Tutor
    Route::get('/cari-tutor', [TutorMatchController::class, 'index'])->name('cari-tutor');
    Route::post('/cari-tutor', [TutorMatchController::class, 'search'])->name('cari-tutor.search');
    Route::get('/tutor/{tutor}', [TutorMatchController::class, 'show'])->name('detail-tutor');

    // Booking
    Route::get('/booking', [MemberBookingController::class, 'index'])->name('booking');
    Route::get('/booking/{tutor}/create', [MemberBookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/{tutor}', [MemberBookingController::class, 'store'])->name('booking.store');
    Route::patch('/booking/{booking}/cancel', [MemberBookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/riwayat', [MemberBookingController::class, 'riwayat'])->name('riwayat');

    // Pembayaran
    Route::get('/pembayaran/{booking}/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran/{booking}', [PembayaranController::class, 'store'])->name('pembayaran.store');

    // Review
    Route::get('/review/{booking}/create', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/review/{booking}', [ReviewController::class, 'store'])->name('review.store');

    // Chat
    Route::get('/chat', [ChatRoomController::class, 'index'])->name('chat.index');
    Route::get('/chat/{chatRoom}', [ChatRoomController::class, 'show'])->name('chat.show');
    Route::post('/chat/{chatRoom}/pesan', [ChatMessageController::class, 'store'])->name('chat.pesan');
});

// ═══════════════════════════════════════════════════════════════
// TUTOR
// ═══════════════════════════════════════════════════════════════
Route::middleware(['auth', 'tutor'])
    ->prefix('tutor')
    ->name('tutor.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [TutorController::class, 'dashboard'])->name('dashboard');

    // Profil
    Route::get('/profil', [TutorController::class, 'editProfil'])->name('profil');
    Route::put('/profil', [TutorController::class, 'simpanProfil'])->name('profil.simpan');

    // Jadwal
    Route::get('/jadwal', [ScheduleController::class, 'index'])->name('jadwal');
    Route::post('/jadwal', [ScheduleController::class, 'store'])->name('jadwal.store');
    Route::delete('/jadwal/{schedule}', [ScheduleController::class, 'destroy'])->name('jadwal.hapus');

    // Area Mengajar
    Route::get('/area-mengajar', [TutorAreaController::class, 'index'])->name('area');
    Route::post('/area-mengajar', [TutorAreaController::class, 'store'])->name('area.store');
    Route::delete('/area-mengajar/{area}', [TutorAreaController::class, 'destroy'])->name('area.hapus');

    // Laporan Sesi
    Route::get('/laporan', [LaporanSesiController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/booking/{booking}', [LaporanSesiController::class, 'create'])->name('laporan.create');
    Route::post('/laporan/booking/{booking}', [LaporanSesiController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{laporanSesi}', [LaporanSesiController::class, 'show'])->name('laporan.show');

    // Pendapatan
    Route::get('/pendapatan', [PendapatanController::class, 'index'])->name('pendapatan');

    // Booking masuk
    Route::get('/booking', [TutorController::class, 'bookingMasuk'])->name('booking');
    Route::patch('/booking/{booking}/{aksi}', [TutorController::class, 'konfirmasiBooking'])->name('booking.aksi');

    // Chat
    Route::get('/chat', [ChatRoomController::class, 'index'])->name('chat.index');
    Route::get('/chat/{chatRoom}', [ChatRoomController::class, 'show'])->name('chat.show');
    Route::post('/chat/{chatRoom}/pesan', [ChatMessageController::class, 'store'])->name('chat.pesan.store');
});

// ═══════════════════════════════════════════════════════════════
// ADMIN
// ═══════════════════════════════════════════════════════════════
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Kelola Tutor & Member
    Route::get('/tutor', [AdminController::class, 'daftarTutor'])->name('tutor');
    Route::get('/member', [AdminController::class, 'daftarMember'])->name('member');

    // Verifikasi
    Route::get('/verifikasi', [VerificationController::class, 'index'])->name('verifikasi');
    Route::get('/verifikasi/tutor/{profile}', [VerificationController::class, 'showTutor'])->name('verifikasi.show');
    Route::patch('/verifikasi/tutor/{profile}/approve', [VerificationController::class, 'approveTutor'])->name('verifikasi.approve');
    Route::patch('/verifikasi/tutor/{profile}/reject', [VerificationController::class, 'rejectTutor'])->name('verifikasi.reject');
    // Tetap support route lama
    Route::patch('/tutor/{profile}/{aksi}', [AdminController::class, 'verifikasiTutor'])->name('verifikasi.lama');

    // Matching Log
    Route::get('/matching-log', [AdminController::class, 'matchingLog'])->name('matching-log');

    // Booking Admin
    Route::get('/booking', [AdminController::class, 'daftarBooking'])->name('booking');
    Route::patch('/booking/{booking}/confirm', [AdminController::class, 'confirmBooking'])->name('booking.confirm');

    // PKS / Kontrak
    Route::get('/pks', [PksController::class, 'index'])->name('pks');
    Route::get('/pks/create/{booking}', [PksController::class, 'create'])->name('pks.create');
    Route::post('/pks/{booking}', [PksController::class, 'store'])->name('pks.store');
    Route::get('/pks/{pks}', [PksController::class, 'show'])->name('pks.show');
    Route::get('/pks/{pks}/download', [PksController::class, 'download'])->name('pks.download');

    // Laporan Sesi
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{laporanSesi}', [LaporanController::class, 'show'])->name('laporan.show');
    Route::patch('/laporan/{laporanSesi}/approve', [LaporanController::class, 'approve'])->name('laporan.approve');

    // Honor Tutor
    Route::get('/honor', [HonorController::class, 'index'])->name('honor');
    Route::patch('/honor/{honor}/bayar', [HonorController::class, 'bayar'])->name('honor.bayar');

    // Pembayaran Member
    Route::get('/pembayaran', [PaymentController::class, 'index'])->name('pembayaran');
    Route::get('/pembayaran/{pembayaran}', [PaymentController::class, 'show'])->name('pembayaran.show');
    Route::patch('/pembayaran/{pembayaran}/verifikasi', [PaymentController::class, 'verifikasi'])->name('pembayaran.verifikasi');

    // Review Moderasi
    Route::get('/review', [ReviewModerationController::class, 'index'])->name('review');
    Route::patch('/review/{review}/approve', [ReviewModerationController::class, 'approve'])->name('review.approve');
    Route::patch('/review/{review}/reject', [ReviewModerationController::class, 'reject'])->name('review.reject');

    // Keuangan
    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/dashboard', [HonorController::class, 'dashboard'])->name('dashboard');
        Route::get('/pembayaran', [PaymentController::class, 'rekap'])->name('pembayaran');
        Route::get('/gaji-tutor', [HonorController::class, 'gajiTutor'])->name('gaji-tutor');
        Route::get('/invoice', [PaymentController::class, 'invoice'])->name('invoice');
        Route::get('/rekap', [HonorController::class, 'rekap'])->name('rekap');
    });
});

require __DIR__.'/auth.php';
