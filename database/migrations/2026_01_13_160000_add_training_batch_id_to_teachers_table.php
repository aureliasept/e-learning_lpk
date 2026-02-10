<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menghubungkan Instruktur dengan Sistem Periode Pelatihan
     * Instruktur di-assign ke Tahun Pelatihan (bukan gelombang)
     */
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Tambah kolom training_year_id - instruktur di-assign ke tahun pelatihan
            $table->foreignId('training_year_id')->nullable()->after('is_karyawan')
                  ->constrained('training_years')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['training_year_id']);
            $table->dropColumn('training_year_id');
        });
    }
};
