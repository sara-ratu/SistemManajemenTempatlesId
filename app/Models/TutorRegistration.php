<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'alamat_domisili', 'no_wa', 'email', 'pendidikan_terakhir',
        'asal_sekolah', 'bidang_keahlian', 'pengalaman_mengajar',
        'tingkat_siswa', 'metode_mengajar', 'hari_tersedia',
        'jam_mengajar', 'area_mengajar', 'pernyataan', 'file_silabus'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
}