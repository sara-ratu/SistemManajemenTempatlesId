<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_sesi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('tutor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_sesi');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedSmallInteger('durasi_menit')->storedAs('TIMESTAMPDIFF(MINUTE, CONCAT(tanggal_sesi, " ", jam_mulai), CONCAT(tanggal_sesi, " ", jam_selesai))')->nullable();
            $table->text('materi_diajarkan');
            $table->text('perkembangan_siswa')->nullable();
            $table->text('catatan_tutor')->nullable();
            $table->string('foto_bukti', 255)->nullable()->comment('Path foto dokumentasi sesi');
            $table->enum('status', ['draft', 'submitted', 'confirmed'])->default('submitted');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index(['tutor_id', 'tanggal_sesi']);
            $table->index(['booking_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_sesi');
    }
};
