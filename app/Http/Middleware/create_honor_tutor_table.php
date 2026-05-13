<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('honor_tutor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pembayaran_id')->constrained('pembayaran')->restrictOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->unsignedInteger('total_pembayaran')->comment('Total yang dibayar member');
            $table->unsignedTinyInteger('komisi_persen');
            $table->unsignedInteger('komisi_nominal');
            $table->unsignedInteger('honor_bersih')->comment('Yang diterima tutor = total - komisi');
            $table->enum('status', ['pending', 'approved', 'transferred', 'cancelled'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('transferred_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('bukti_transfer', 255)->nullable();
            $table->string('rekening_tujuan', 100)->nullable();
            $table->string('atas_nama', 100)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['tutor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('honor_tutor');
    }
};
