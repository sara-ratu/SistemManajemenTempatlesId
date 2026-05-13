<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();

            // relasi dari booking (ini yang benar untuk sistem kamu)
            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->cascadeOnDelete();

            $table->foreignId('tutor_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('member_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('status', ['active', 'closed'])
                ->default('active');

            $table->timestamp('closed_at')->nullable();

            $table->timestamps();

            // 1 booking = 1 chat room
            $table->unique('booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
