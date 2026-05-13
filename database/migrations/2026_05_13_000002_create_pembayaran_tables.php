<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Tabel Pembayaran (dari murid) ─────────────────────────
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('murid_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('jumlah', 12, 2);
            $table->string('metode')->default('transfer');     // transfer, qris, dll
            $table->string('bukti_transfer')->nullable();      // path file
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ── Tabel Honor Tutor (dari platform ke tutor) ────────────
        Schema::create('honor_tutors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')->constrained('pembayarans')->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tutor_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('jumlah_bruto', 12, 2);
            $table->decimal('komisi_platform', 5, 2)->default(10.00); // persen
            $table->decimal('jumlah_honor', 12, 2);
            $table->enum('status', ['pending', 'ditransfer'])->default('pending');
            $table->string('rekening_bank')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->timestamp('ditransfer_at')->nullable();
            $table->foreignId('ditransfer_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('honor_tutors');
        Schema::dropIfExists('pembayarans');
    }
};
