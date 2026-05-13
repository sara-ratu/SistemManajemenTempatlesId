<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_room_id')->constrained('chat_rooms')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tipe', ['text', 'file', 'image'])->default('text');
            $table->text('isi')->nullable()->comment('Isi pesan teks');
            $table->string('file_path', 255)->nullable()->comment('Path file/gambar yang dikirim');
            $table->string('file_name', 255)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Index utama untuk performa query room
            $table->index(['chat_room_id', 'created_at']);
            $table->index(['sender_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
