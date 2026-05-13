<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('users')->cascadeOnDelete();
            $table->string('invoice_no', 50)->unique()->comment('INV/2024/001');
            $table->unsignedInteger('jumlah_sesi');
            $table->unsignedInteger('harga_per_sesi');
            $table->unsignedInteger('subtotal');
            $table->unsignedTinyInteger('komisi_persen')->default(10);
            $table->unsignedInteger('komisi_nominal');
            $table->unsignedInteger('total_bayar');
            $table->enum('metode_bayar', ['transfer', 'tunai', 'qris', 'virtual_account'])->nullable();
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('bukti_bayar', 255)->nullable()->comment('Path foto/file bukti transfer');
            $table->timestamp('paid_at')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'status']);
            $table->index(['booking_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
