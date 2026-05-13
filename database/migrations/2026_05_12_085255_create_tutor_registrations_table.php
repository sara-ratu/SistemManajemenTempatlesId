<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tutor_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat_domisili');
            $table->string('no_wa');
            $table->string('email');
            $table->string('pendidikan_terakhir');
            $table->string('asal_sekolah');
            $table->string('bidang_keahlian');
            $table->text('pengalaman_mengajar')->nullable();
            $table->string('tingkat_siswa');
            $table->string('metode_mengajar');
            $table->string('hari_tersedia');
            $table->string('jam_mengajar');
            $table->string('area_mengajar');
            $table->text('pernyataan')->nullable();
            $table->string('file_silabus')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tutor_registrations');
    }
};