<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- Jangan lupa ini

return new class extends Migration
{
    public function up()
    {
        // Kita ubah kolom 'classroom' agar BOLEH KOSONG (NULL)
        // Jadi kalau di form gak diisi, database tidak akan marah
        DB::statement("ALTER TABLE students MODIFY COLUMN classroom VARCHAR(255) NULL");
    }

    public function down()
    {
        // Kembalikan ke wajib isi (NOT NULL) jika rollback
        // DB::statement("ALTER TABLE students MODIFY COLUMN classroom VARCHAR(255) NOT NULL");
    }
};