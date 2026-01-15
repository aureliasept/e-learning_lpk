<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- WAJIB ADA

return new class extends Migration
{
    public function up(): void
    {
        // Kita paksa ubah kolom role agar menerima 'student'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'instruktur', 'student') NOT NULL DEFAULT 'student'");
    }

    public function down(): void
    {
        // Kembalikan ke asal (opsional)
        // DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'instruktur') NOT NULL");
    }
};