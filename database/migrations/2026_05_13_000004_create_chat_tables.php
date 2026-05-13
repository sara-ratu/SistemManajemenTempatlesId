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
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('tutor_id');
            $table->unsignedBigInteger('member_id');
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->foreign('tutor_id')->references('id')->on('users');
            $table->foreign('member_id')->references('id')->on('users');
            $table->index(['tutor_id', 'status']);
            $table->index(['member_id', 'status']);
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_room_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('sender_id');
            $table->enum('tipe', ['text', 'file', 'image'])->default('text');
            $table->text('isi')->nullable();
            $table->string('file_path')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users');
            $table->index(['chat_room_id', 'created_at']);
            $table->index(['chat_room_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_rooms');
    }
};
