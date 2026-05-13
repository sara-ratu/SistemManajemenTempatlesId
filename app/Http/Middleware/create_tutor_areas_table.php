<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutor_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('users')->cascadeOnDelete();
            $table->string('provinsi', 100)->nullable();
            $table->string('kota_kabupaten', 100);
            $table->string('kecamatan', 100)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->decimal('radius_km', 5, 2)->default(5.00)->comment('Jarak maksimal tutor bersedia datang (km)');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_primary')->default(false)->comment('Area utama tutor');
            $table->timestamps();

            $table->index(['kota_kabupaten', 'kecamatan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_areas');
    }
};
