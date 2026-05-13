<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tutor_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating')->comment('1-5');
            $table->text('komentar')->nullable();
            // Aspek penilaian detail (opsional, 1-5)
            $table->unsignedTinyInteger('kejelasan_materi')->nullable();
            $table->unsignedTinyInteger('ketepatan_waktu')->nullable();
            $table->unsignedTinyInteger('keramahan')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['booking_id', 'member_id'], 'uq_feedback_booking_member');
            $table->index(['tutor_id', 'rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
