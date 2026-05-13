<?php

namespace Database\Seeders;

use App\Models\TutorRegistration;
use Illuminate\Database\Seeder;

class TutorRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        TutorRegistration::create([
            'nama_lengkap'        => 'NABELA DUWI RAHAYU',
            'jenis_kelamin'       => 'Perempuan',
            'tempat_lahir'        => 'MADIUN',
            'tanggal_lahir'       => '2005-09-12',
            'alamat_domisili'     => 'Dolopo, Madiun',
            'no_wa'               => '12345678909876',
            'email'               => 'duwirhyu657@gmail.com',
            'pendidikan_terakhir' => 'SMA',
            'asal_sekolah'        => 'SMASMA',
            'bidang_keahlian'     => 'Matematika',
            'pengalaman_mengajar' => 'Pernah mengajar les privat Matematika SD selama 1 tahun',
            'tingkat_siswa'       => 'SD, SMP',
            'metode_mengajar'     => 'Online & Offline',
            'hari_tersedia'       => 'Senin, Rabu, Jumat, Sabtu',
            'jam_mengajar'        => '15.00 - 19.00',
            'area_mengajar'       => 'Madiun dan sekitarnya',
            'pernyataan'          => 'Saya siap mengajar dengan baik dan bertanggung jawab',
            'file_silabus'        => null,
        ]);

        TutorRegistration::create([
            'nama_lengkap'        => 'NAYLA PUTRI',
            'jenis_kelamin'       => 'Perempuan',
            'tempat_lahir'        => 'MADIUN',
            'tanggal_lahir'       => '2006-05-20',
            'alamat_domisili'     => 'Kota Madiun',
            'no_wa'               => '081234567890',
            'email'               => 'nayla@gmail.com',
            'pendidikan_terakhir' => 'S1 Pendidikan',
            'asal_sekolah'        => 'Universitas Negeri Malang',
            'bidang_keahlian'     => 'Bahasa Inggris',
            'pengalaman_mengajar' => '2 tahun mengajar les Bahasa Inggris',
            'tingkat_siswa'       => 'SMP, SMA',
            'metode_mengajar'     => 'Online',
            'hari_tersedia'       => 'Selasa, Kamis, Sabtu',
            'jam_mengajar'        => '16.00 - 20.00',
            'area_mengajar'       => 'Online seluruh Indonesia',
            'pernyataan'          => 'Siap membantu siswa meningkatkan kemampuan bahasa Inggris',
            'file_silabus'        => null,
        ]);

        echo "✅ Data contoh Tutor berhasil ditambahkan!\n";
    }
}