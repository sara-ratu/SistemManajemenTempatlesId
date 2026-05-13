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
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable()->after('bio');
            $table->date('tgl_lahir')->nullable()->after('jenis_kelamin');
            $table->string('tempat_lahir', 100)->nullable()->after('tgl_lahir');
            $table->unsignedTinyInteger('pengalaman_tahun')->default(0)->after('tempat_lahir');

            // Metode & jenjang
            $table->enum('metode', ['online', 'offline', 'keduanya'])->default('keduanya')->after('pengalaman_tahun');
            $table->string('jenjang_mengajar', 255)->nullable()->comment('SD,SMP,SMA,Kuliah,Umum')->after('metode');

            // Kontak
            $table->string('no_wa', 20)->nullable()->after('jenjang_mengajar');

            // Status verifikasi
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending')->after('no_wa');
            $table->unsignedBigInteger('verified_by')->nullable()->after('status');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('verified_at');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            $table->text('alasan_reject')->nullable()->after('rejected_at');

            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('rejected_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tutor_profiles', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn([
                'jenis_kelamin', 'tgl_lahir', 'tempat_lahir', 'pengalaman_tahun',
                'metode', 'jenjang_mengajar', 'no_wa',
                'status', 'verified_by', 'verified_at',
                'rejected_by', 'rejected_at', 'alasan_reject',
            ]);
        });
    }
};
