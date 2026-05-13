<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tutor_profiles', function (Blueprint $table) {
            // Data diri
            $table->string('jenis_kelamin', 20)->nullable()->after('user_id');
            $table->string('tempat_lahir', 100)->nullable()->after('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->unsignedTinyInteger('pengalaman')->default(0)->after('tanggal_lahir')
                  ->comment('Pengalaman mengajar dalam tahun');

            // Info mengajar
            $table->string('metode_mengajar', 50)->nullable()->after('pengalaman')
                  ->comment('Online / Offline / Online & Offline — dipisah koma');
            $table->string('jenjang', 100)->nullable()->after('metode_mengajar')
                  ->comment('SD,SMP,SMA,Mahasiswa,Umum — dipisah koma');

            // Dokumen (tanpa KTP)
            $table->string('foto_profil')->nullable()->after('jenjang');
            $table->string('file_ijazah')->nullable()->after('foto_profil');
            $table->string('file_sertifikat')->nullable()->after('file_ijazah');
        });
    }

    public function down(): void
    {
        Schema::table('tutor_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_kelamin',
                'tempat_lahir',
                'tanggal_lahir',
                'pengalaman',
                'metode_mengajar',
                'jenjang',
                'foto_profil',
                'file_ijazah',
                'file_sertifikat',
            ]);
        });
    }
};
