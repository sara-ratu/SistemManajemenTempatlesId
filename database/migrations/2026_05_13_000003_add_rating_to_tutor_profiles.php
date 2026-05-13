<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom rating ke tutor_profiles (tabel reviews sudah ada)
        Schema::table('tutor_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('tutor_profiles', 'rating_avg')) {
                $table->decimal('rating_avg', 3, 2)->default(0)->after('bio');
            }
            if (! Schema::hasColumn('tutor_profiles', 'rating_count')) {
                $table->unsignedInteger('rating_count')->default(0)->after('rating_avg');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tutor_profiles', function (Blueprint $table) {
            $table->dropColumn(['rating_avg', 'rating_count']);
        });
    }
};
