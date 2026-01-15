<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. BUAT AKUN ADMIN (Password: 12345678)
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        // 2. BUAT MODUL 1: Dasar - Dasar
        $modul1 = Course::create([
            'title' => 'Modul 1: Dasar - Dasar',
            'description' => 'Modul pembelajaran tingkat dasar untuk pemula.',
            'level' => 'pemula',
        ]);

        // Otomatis Isi Bab 1 - Bab 25 untuk Modul 1
        for ($i = 1; $i <= 25; $i++) {
            Material::create([
                'course_id' => $modul1->id,
                'title' => "Bab $i", // Judul hanya "Bab 1", "Bab 2", dst.
                'content' => '',
                'order' => $i
            ]);
        }

        // 3. BUAT MODUL 2: Lanjutan
        $modul2 = Course::create([
            'title' => 'Modul 2: Lanjutan',
            'description' => 'Modul pembelajaran tingkat lanjut.',
            'level' => 'menengah',
        ]);

        // Otomatis Isi Bab 1 - Bab 25 untuk Modul 2
        for ($i = 1; $i <= 25; $i++) {
            Material::create([
                'course_id' => $modul2->id,
                'title' => "Bab $i", // Judul hanya "Bab 1", "Bab 2", dst.
                'content' => '',
                'order' => $i
            ]);
        }
    }
}