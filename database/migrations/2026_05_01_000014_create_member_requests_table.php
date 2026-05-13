<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->restrictOnDelete();
            $table->enum('jenjang', ['SD', 'SMP', 'SMA', 'Kuliah', 'Umum']);
            $table->enum('metode', ['online', 'offline', 'keduanya'])->default('keduanya');
            $table->string('kota_kabupaten', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->unsignedSmallInteger('budget_min')->nullable()->comment('Ribu rupiah per sesi');
            $table->unsignedSmallInteger('budget_max')->nullable()->comment('Ribu rupiah per sesi');
            $table->text('catatan')->nullable()->comment('Kebutuhan khusus dari member');
            $table->enum('status', ['open', 'matched', 'closed'])->default('open');
            $table->timestamp('matched_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'kota_kabupaten']);
            $table->index(['member_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_requests');
    }
};
