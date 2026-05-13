<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            // SD
            ['nama_mapel' => 'Matematika SD',        'jenjang' => 'SD',     'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Bahasa Indonesia SD',   'jenjang' => 'SD',     'kategori' => 'Bahasa'],
            ['nama_mapel' => 'IPA SD',                'jenjang' => 'SD',     'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'IPS SD',                'jenjang' => 'SD',     'kategori' => 'Sosial'],
            ['nama_mapel' => 'Bahasa Inggris SD',     'jenjang' => 'SD',     'kategori' => 'Bahasa'],
            ['nama_mapel' => 'PKn SD',                'jenjang' => 'SD',     'kategori' => 'Sosial'],
            // SMP
            ['nama_mapel' => 'Matematika SMP',        'jenjang' => 'SMP',    'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Fisika SMP',            'jenjang' => 'SMP',    'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Kimia SMP',             'jenjang' => 'SMP',    'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Biologi SMP',           'jenjang' => 'SMP',    'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Bahasa Indonesia SMP',  'jenjang' => 'SMP',    'kategori' => 'Bahasa'],
            ['nama_mapel' => 'Bahasa Inggris SMP',    'jenjang' => 'SMP',    'kategori' => 'Bahasa'],
            ['nama_mapel' => 'IPS SMP',               'jenjang' => 'SMP',    'kategori' => 'Sosial'],
            ['nama_mapel' => 'PKn SMP',               'jenjang' => 'SMP',    'kategori' => 'Sosial'],
            ['nama_mapel' => 'Bahasa Jawa SMP',       'jenjang' => 'SMP',    'kategori' => 'Bahasa'],
            // SMA
            ['nama_mapel' => 'Matematika SMA',        'jenjang' => 'SMA',    'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Matematika Peminatan',  'jenjang' => 'SMA',    'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Fisika SMA',            'jenjang' => 'SMA',    'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Kimia SMA',             'jenjang' => 'SMA',    'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Biologi SMA',           'jenjang' => 'SMA',    'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Bahasa Indonesia SMA',  'jenjang' => 'SMA',    'kategori' => 'Bahasa'],
            ['nama_mapel' => 'Bahasa Inggris SMA',    'jenjang' => 'SMA',    'kategori' => 'Bahasa'],
            ['nama_mapel' => 'Ekonomi SMA',           'jenjang' => 'SMA',    'kategori' => 'Sosial'],
            ['nama_mapel' => 'Geografi SMA',          'jenjang' => 'SMA',    'kategori' => 'Sosial'],
            ['nama_mapel' => 'Sejarah SMA',           'jenjang' => 'SMA',    'kategori' => 'Sosial'],
            ['nama_mapel' => 'Sosiologi SMA',         'jenjang' => 'SMA',    'kategori' => 'Sosial'],
            ['nama_mapel' => 'Akuntansi SMA',         'jenjang' => 'SMA',    'kategori' => 'Sosial'],
            ['nama_mapel' => 'Bahasa Arab SMA',       'jenjang' => 'SMA',    'kategori' => 'Bahasa'],
            ['nama_mapel' => 'Bahasa Jepang SMA',     'jenjang' => 'SMA',    'kategori' => 'Bahasa'],
            // Persiapan Ujian
            ['nama_mapel' => 'Persiapan UTBK/SNBT',  'jenjang' => 'SMA',    'kategori' => 'Ujian & Tes'],
            ['nama_mapel' => 'Persiapan UN SMP',      'jenjang' => 'SMP',    'kategori' => 'Ujian & Tes'],
            ['nama_mapel' => 'Persiapan UN SD',       'jenjang' => 'SD',     'kategori' => 'Ujian & Tes'],
            ['nama_mapel' => 'IELTS',                 'jenjang' => 'Umum',   'kategori' => 'Ujian & Tes'],
            ['nama_mapel' => 'TOEFL',                 'jenjang' => 'Umum',   'kategori' => 'Ujian & Tes'],
            ['nama_mapel' => 'TOEIC',                 'jenjang' => 'Umum',   'kategori' => 'Ujian & Tes'],
            // Kuliah
            ['nama_mapel' => 'Kalkulus',              'jenjang' => 'Kuliah', 'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Statistika',            'jenjang' => 'Kuliah', 'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Aljabar Linear',        'jenjang' => 'Kuliah', 'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Fisika Dasar',          'jenjang' => 'Kuliah', 'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Kimia Dasar',           'jenjang' => 'Kuliah', 'kategori' => 'Sains & Matematika'],
            ['nama_mapel' => 'Akuntansi Dasar',       'jenjang' => 'Kuliah', 'kategori' => 'Sosial'],
            ['nama_mapel' => 'Ekonomi Mikro',         'jenjang' => 'Kuliah', 'kategori' => 'Sosial'],
            ['nama_mapel' => 'Ekonomi Makro',         'jenjang' => 'Kuliah', 'kategori' => 'Sosial'],
            // Teknologi
            ['nama_mapel' => 'Pemrograman Python',    'jenjang' => 'Umum',   'kategori' => 'Teknologi'],
            ['nama_mapel' => 'Pemrograman Web',       'jenjang' => 'Umum',   'kategori' => 'Teknologi'],
            ['nama_mapel' => 'Pemrograman Java',      'jenjang' => 'Umum',   'kategori' => 'Teknologi'],
            ['nama_mapel' => 'Database (SQL)',         'jenjang' => 'Umum',   'kategori' => 'Teknologi'],
            ['nama_mapel' => 'Microsoft Excel',       'jenjang' => 'Umum',   'kategori' => 'Teknologi'],
            // Bahasa Umum
            ['nama_mapel' => 'Bahasa Inggris Umum',   'jenjang' => 'Umum',   'kategori' => 'Bahasa'],
            ['nama_mapel' => 'Bahasa Arab Umum',      'jenjang' => 'Umum',   'kategori' => 'Bahasa'],
            ['nama_mapel' => 'Bahasa Mandarin',       'jenjang' => 'Umum',   'kategori' => 'Bahasa'],
            ['nama_mapel' => 'Bahasa Jepang Umum',    'jenjang' => 'Umum',   'kategori' => 'Bahasa'],
            ['nama_mapel' => 'Bahasa Korea',          'jenjang' => 'Umum',   'kategori' => 'Bahasa'],
            // Seni & Agama
            ['nama_mapel' => 'Seni Musik',            'jenjang' => 'Umum',   'kategori' => 'Seni'],
            ['nama_mapel' => 'Menggambar & Melukis',  'jenjang' => 'Umum',   'kategori' => 'Seni'],
            ['nama_mapel' => 'Mengaji / Al-Quran',    'jenjang' => 'Umum',   'kategori' => 'Agama'],
            ['nama_mapel' => 'Fiqih',                 'jenjang' => 'Umum',   'kategori' => 'Agama'],
        ];

        $now = now();

        foreach ($subjects as $subject) {
            DB::table('subjects')->updateOrInsert(
                ['nama_mapel' => $subject['nama_mapel']],
                array_merge($subject, [
                    'is_active'  => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
