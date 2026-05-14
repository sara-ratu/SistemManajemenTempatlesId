<?php

use Illuminate\Support\Facades\Route;

// ── Controllers ───────────────────────────────────────────────
use App\Http\Controllers\TutorMatchController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\MuridDashboardController;
use App\Http\Controllers\ProfileController;

// Admin Controllers
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

// ── Profile ───────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Daftar Tutor Publik ───────────────────────────────────────
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

    Route::get('/dashboard', [MuridDashboardController::class, 'index'])->name('dashboard');

    Route::get('/cari-tutor', [TutorMatchController::class, 'index'])->name('cari-tutor');
    Route::post('/cari-tutor', [TutorMatchController::class, 'search'])->name('cari-tutor.search');
    Route::get('/tutor/{tutor}', [TutorMatchController::class, 'show'])->name('detail-tutor');

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

// ═══════════════════════════════════════════════════════════════
// TUTOR
// ═══════════════════════════════════════════════════════════════
Route::middleware(['auth', 'tutor'])
    ->prefix('tutor')
    ->name('tutor.')
    ->group(function () {

    Route::get('/dashboard', [TutorController::class, 'dashboard'])->name('dashboard');

    Route::get('/profil', [TutorController::class, 'editProfil'])->name('profil');
    Route::put('/profil', [TutorController::class, 'simpanProfil'])->name('profil.simpan');

    Route::resource('jadwal', ScheduleController::class)->except(['show', 'edit', 'update']);
    Route::resource('area-mengajar', TutorAreaController::class)->except(['show', 'edit', 'update']);

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

// ═══════════════════════════════════════════════════════════════
// ADMIN
// ═══════════════════════════════════════════════════════════════
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/murid', [AdminController::class, 'daftarMember'])->name('admin.murid');

    // Tutor Management
    Route::resource('tutor', App\Http\Controllers\Admin\TutorController::class);

    // Verifikasi
    Route::get('/verifikasi', [VerificationController::class, 'index'])->name('verifikasi');
    Route::get('/verifikasi/tutor/{profile}', [VerificationController::class, 'showTutor'])->name('verifikasi.show');
    Route::patch('/verifikasi/tutor/{profile}/approve', [VerificationController::class, 'approveTutor'])->name('verifikasi.approve');
    Route::patch('/verifikasi/tutor/{profile}/reject', [VerificationController::class, 'rejectTutor'])->name('verifikasi.reject');

    // Lainnya
    Route::get('/member', [AdminController::class, 'daftarMember'])->name('member');
    Route::get('/matching-log', [AdminController::class, 'matchingLog'])->name('matching-log');
    Route::get('/booking', [AdminController::class, 'daftarBooking'])->name('booking');
    Route::patch('/booking/{booking}/confirm', [AdminController::class, 'confirmBooking'])->name('booking.confirm');

    // Resource Lain
    Route::resource('pks', PksController::class)->except(['edit', 'update', 'destroy']);

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{laporanSesi}', [LaporanController::class, 'show'])->name('laporan.show');
    Route::patch('/laporan/{laporanSesi}/approve', [LaporanController::class, 'approve'])->name('laporan.approve');

    // ==================== HONOR TUTOR ====================
    Route::prefix('honor')
         ->name('honor.')
         ->group(function () {

            Route::get('/', [HonorController::class, 'index'])->name('index');
            Route::patch('{honor}/transfer', [HonorController::class, 'transfer'])->name('transfer');
    });

    Route::get('/pembayaran', [PaymentController::class, 'index'])->name('pembayaran');
    Route::get('/pembayaran/{pembayaran}', [PaymentController::class, 'show'])->name('pembayaran.show');
    Route::patch('/pembayaran/{pembayaran}/verifikasi', [PaymentController::class, 'verifikasi'])->name('pembayaran.verifikasi');

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


    Route::prefix('admin/honor')->middleware(['auth', 'admin'])->name('admin.honor.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'honorIndex'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\AdminController::class, 'honorCreate'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\AdminController::class, 'honorStore'])->name('store');
        Route::get('/{honor}/edit', [App\Http\Controllers\Admin\AdminController::class, 'honorEdit'])->name('edit');
        Route::put('/{honor}', [App\Http\Controllers\Admin\AdminController::class, 'honorUpdate'])->name('update');
        Route::delete('/{honor}', [App\Http\Controllers\Admin\AdminController::class, 'honorDestroy'])->name('destroy');
        Route::patch('/{honor}/transfer', [App\Http\Controllers\Admin\AdminController::class, 'honorTransfer'])->name('transfer');
    });

        // ==================== ADMIN MEMBER ROUTES ====================
        Route::prefix('member')
            ->name('member.')
            ->group(function () {

                Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'daftarMember'])
                    ->name('index');

                Route::get('/{user}/edit', [App\Http\Controllers\Admin\AdminController::class, 'memberEdit'])
                    ->name('edit');

                Route::put('/{user}', [App\Http\Controllers\Admin\AdminController::class, 'memberUpdate'])
                    ->name('update');

                Route::patch('/{user}/toggle-status', [App\Http\Controllers\Admin\AdminController::class, 'memberToggleStatus'])
                    ->name('toggle-status');
        });
});

require __DIR__.'/auth.php';
