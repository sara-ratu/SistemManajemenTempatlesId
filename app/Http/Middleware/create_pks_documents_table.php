<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pks_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('users')->cascadeOnDelete();
            $table->string('nomor_pks', 100)->unique()->comment('Nomor dokumen PKS, e.g. PKS/2024/001');
            $table->date('tanggal_terbit');
            $table->date('tanggal_berlaku');
            $table->date('tanggal_berakhir')->nullable();
            $table->string('file_path', 255)->nullable()->comment('Path PDF PKS yang sudah ditandatangani');
            $table->enum('status', ['draft', 'sent', 'signed', 'expired', 'terminated'])->default('draft');
            $table->timestamp('signed_at')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users')->restrictOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['tutor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pks_documents');
    }
};
