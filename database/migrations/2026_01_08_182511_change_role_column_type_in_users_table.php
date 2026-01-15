<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- JANGAN LUPA INI

return new class extends Migration
{
    public function up()
    {
        // Ubah kolom 'role' menjadi VARCHAR(50) agar bisa menampung 'instructor'
        // Kita pakai DB::statement agar lebih kuat dan tidak butuh doctrine/dbal
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'student'");
    }

    public function down()
    {
        // Kembalikan ke ENUM jika rollback (Opsional)
        // DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'student') NOT NULL DEFAULT 'student'");
    }
};