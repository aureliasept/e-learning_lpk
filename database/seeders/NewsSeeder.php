<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\User;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authorId = User::query()->value('id') ?? 1;

        News::create([
            'title' => 'Alumni Batch 2025 Diterima Kerja di Jepang',
            'content' => 'Selamat kepada alumni LPK Garuda Bakti Internasional batch 2025 yang telah resmi diterima bekerja di perusahaan mitra di Jepang.',
            'author_id' => $authorId,
            'is_active' => true,
        ]);

        News::create([
            'title' => 'Jadwal Keberangkatan Peserta Program Magang',
            'content' => 'Keberangkatan peserta program magang ke Jepang dijadwalkan pada bulan depan. Harap cek kembali kelengkapan berkas dan persiapan keberangkatan.',
            'author_id' => $authorId,
            'is_active' => true,
        ]);

        News::create([
            'title' => 'Pembukaan Pendaftaran Pelatihan Bahasa Jepang',
            'content' => 'Pendaftaran pelatihan bahasa Jepang gelombang baru telah dibuka. Peserta yang berminat silakan mendaftar melalui bagian administrasi LPK.',
            'author_id' => $authorId,
            'is_active' => true,
        ]);
    }
}
