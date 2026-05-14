<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tutor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->integer('harga_min')->default(0);
            $table->integer('harga_max')->default(0);
            $table->string('pendidikan')->nullable();
            $table->string('universitas')->nullable();
            $table->string('dokumen_ktp')->nullable();
            $table->string('dokumen_ijazah')->nullable();
            $table->decimal('rating_rata', 3, 2)->default(0);
            $table->integer('total_review')->default(0);
            $table->integer('total_Member')->default(0);
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_profiles');
    }
};
