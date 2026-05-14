<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matching_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Member_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tutor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('skor_lokasi', 5, 2)->default(0);
            $table->decimal('skor_mapel', 5, 2)->default(0);
            $table->decimal('skor_harga', 5, 2)->default(0);
            $table->decimal('skor_jadwal', 5, 2)->default(0);
            $table->decimal('skor_rating', 5, 2)->default(0);
            $table->decimal('skor_total', 5, 2)->default(0);
            $table->json('kriteria_input')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matching_logs');
    }
};
