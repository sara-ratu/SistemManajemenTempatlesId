<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
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

        // Jalankan seeder tutor
        $this->call(TutorSeeder::class);
    }
}
