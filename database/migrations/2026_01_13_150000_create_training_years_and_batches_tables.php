<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Training Years - Hanya sebagai label angkatan untuk laporan
     * TIDAK ADA gelombang/batch lagi
     */
    public function up(): void
    {
        // Create training_years table (label angkatan saja)
        Schema::create('training_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "2025", "2026"
            $table->timestamps();
        });

        // Add training_year_id to students table
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('training_year_id')->nullable()->after('academic_year_id')
                  ->constrained('training_years')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove training_year_id from students
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['training_year_id']);
            $table->dropColumn('training_year_id');
        });

        // Drop training_years table
        Schema::dropIfExists('training_years');
    }
};
