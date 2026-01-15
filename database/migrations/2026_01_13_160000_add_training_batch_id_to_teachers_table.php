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
     * Instruktur dapat di-assign ke Gelombang tertentu
     */
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Tambah kolom training_batch_id - instruktur bisa di-assign ke gelombang tertentu
            $table->foreignId('training_batch_id')->nullable()->after('is_karyawan')
                  ->constrained('training_batches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['training_batch_id']);
            $table->dropColumn('training_batch_id');
        });
    }
};
