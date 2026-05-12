<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TutorProfile;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TutorSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun admin
        User::create([
            'name'     => 'Admin TutorMatch',
            'email'    => 'admin@tempatles.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'kota'     => 'Madiun',
        ]);

        // Buat mata pelajaran
        $mapel = [
            'Matematika', 'Bahasa Inggris', 'Fisika',
            'Kimia', 'Biologi', 'Bahasa Indonesia',
        ];
        foreach ($mapel as $m) {
            Subject::create(['nama_mapel' => $m, 'is_active' => true]);
        }

        // Data tutor dummy
        $tutors = [
            ['name'=>'Budi Santoso',  'kota'=>'Madiun','lat'=>-7.6298,'lon'=>111.5239,'hmin'=>50000, 'hmax'=>80000, 'rating'=>4.8,'mapel'=>[1,2]],
            ['name'=>'Siti Rahayu',   'kota'=>'Madiun','lat'=>-7.6350,'lon'=>111.5300,'hmin'=>60000, 'hmax'=>100000,'rating'=>4.5,'mapel'=>[3,4]],
            ['name'=>'Ahmad Fauzi',   'kota'=>'Madiun','lat'=>-7.6200,'lon'=>111.5100,'hmin'=>40000, 'hmax'=>70000, 'rating'=>4.2,'mapel'=>[1,3]],
            ['name'=>'Dewi Lestari',  'kota'=>'Madiun','lat'=>-7.6400,'lon'=>111.5400,'hmin'=>75000, 'hmax'=>120000,'rating'=>4.9,'mapel'=>[2,6]],
            ['name'=>'Rizky Pratama', 'kota'=>'Madiun','lat'=>-7.6100,'lon'=>111.5050,'hmin'=>50000, 'hmax'=>90000, 'rating'=>0,  'mapel'=>[5,6]],
        ];

        foreach ($tutors as $t) {
            $user = User::create([
                'name'      => $t['name'],
                'email'     => strtolower(str_replace(' ', '.', $t['name'])).'@tutortest.com',
                'password'  => Hash::make('password'),
                'role'      => 'tutor',
                'kota'      => $t['kota'],
                'latitude'  => $t['lat'],
                'longitude' => $t['lon'],
            ]);

            $profile = TutorProfile::create([
                'user_id'           => $user->id,
                'harga_min'         => $t['hmin'],
                'harga_max'         => $t['hmax'],
                'rating_rata'       => $t['rating'],
                'total_review'      => $t['rating'] > 0 ? 10 : 0,
                'status_verifikasi' => 'verified',
                'is_active'         => true,
            ]);

            $profile->subjects()->attach($t['mapel']);

            foreach (['Senin','Selasa','Rabu','Kamis','Jumat'] as $hari) {
                Schedule::create([
                    'tutor_profile_id' => $profile->id,
                    'hari'             => $hari,
                    'jam_mulai'        => '08:00',
                    'jam_selesai'      => '17:00',
                    'is_available'     => true,
                ]);
            }
        }
    }
}
