<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Cek dulu agar tidak error jika kolom sudah ada sebagian
            if (!Schema::hasColumn('teachers', 'position')) {
                $table->string('position')->nullable()->after('phone'); // Jabatan (Kepala Instruktur, dll)
            }
            if (!Schema::hasColumn('teachers', 'birth_place')) {
                $table->string('birth_place')->nullable()->after('position');
            }
            if (!Schema::hasColumn('teachers', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('birth_place');
            }
            if (!Schema::hasColumn('teachers', 'is_reguler')) {
                $table->boolean('is_reguler')->default(false)->after('birth_date');
            }
            if (!Schema::hasColumn('teachers', 'is_karyawan')) {
                $table->boolean('is_karyawan')->default(false)->after('is_reguler');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['position', 'birth_place', 'birth_date', 'is_reguler', 'is_karyawan']);
        });
    }
};