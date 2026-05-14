<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('honor_tutors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('jumlah_bruto', 15, 2);
            $table->integer('komisi_platform')->default(0);
            $table->decimal('jumlah_honor', 15, 2);
            $table->string('rekening_bank')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->enum('status', ['pending', 'ditransfer'])->default('pending');
            $table->string('bukti_transfer')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_transfer')->nullable();
            $table->foreignId('transfer_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('honor_tutors');
    }
};
