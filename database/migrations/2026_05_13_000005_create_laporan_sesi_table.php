<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_sesis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('tutor_id');
            $table->date('tanggal_sesi');
            $table->text('materi_diajarkan');
            $table->text('perkembangan_murid')->nullable();
            $table->text('kendala')->nullable();
            $table->text('catatan_tambahan')->nullable();
            $table->enum('status_laporan', ['draft', 'submitted', 'approved'])->default('draft');
            $table->timestamps();

            $table->foreign('tutor_id')->references('id')->on('users');
            $table->index(['tutor_id', 'status_laporan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_sesis');
    }
};
