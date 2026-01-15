<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Refactoring: Academic Years → Training Years + Training Batches
     * Hierarchy: Year → Batch → Students
     */
    public function up(): void
    {
        // 1. Create training_years table (replaces academic_years concept)
        Schema::create('training_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "2025", "2026"
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // 2. Create training_batches table (Gelombang per Tahun)
        Schema::create('training_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_year_id')->constrained('training_years')->onDelete('cascade');
            $table->string('name'); // Contoh: "Gelombang 1", "Gelombang 2"
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });

        // 3. Add training_batch_id to students table
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('training_batch_id')->nullable()->after('academic_year_id')
                  ->constrained('training_batches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove training_batch_id from students
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['training_batch_id']);
            $table->dropColumn('training_batch_id');
        });

        // Drop tables in reverse order
        Schema::dropIfExists('training_batches');
        Schema::dropIfExists('training_years');
    }
};
