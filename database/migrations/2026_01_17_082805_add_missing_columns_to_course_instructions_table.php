<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom-kolom yang kurang ke tabel course_instructions
     */
    public function up(): void
    {
        Schema::table('course_instructions', function (Blueprint $table) {
            // Cek dan tambah kolom training_year_id jika belum ada (changed from batch)
            if (!Schema::hasColumn('course_instructions', 'training_year_id')) {
                $table->foreignId('training_year_id')->nullable()->after('id')->constrained('training_years')->onDelete('cascade');
            }

            // Cek dan tambah kolom instructor_id jika belum ada
            if (!Schema::hasColumn('course_instructions', 'instructor_id')) {
                $table->foreignId('instructor_id')->nullable()->after('training_year_id')->constrained('users')->onDelete('cascade');
            }

            // Cek dan tambah kolom title jika belum ada
            if (!Schema::hasColumn('course_instructions', 'title')) {
                $table->string('title')->nullable()->after('instructor_id');
            }

            // Cek dan tambah kolom description jika belum ada
            if (!Schema::hasColumn('course_instructions', 'description')) {
                $table->text('description')->nullable()->after('title');
            }

            // Cek dan tambah kolom file_path jika belum ada
            if (!Schema::hasColumn('course_instructions', 'file_path')) {
                $table->string('file_path')->nullable()->after('description');
            }

            // Cek dan tambah kolom is_task jika belum ada
            if (!Schema::hasColumn('course_instructions', 'is_task')) {
                $table->boolean('is_task')->default(false)->after('file_path');
            }

            // Cek dan tambah kolom deadline jika belum ada
            if (!Schema::hasColumn('course_instructions', 'deadline')) {
                $table->datetime('deadline')->nullable()->after('is_task');
            }

            // Cek dan tambah kolom allowed_response_type jika belum ada
            if (!Schema::hasColumn('course_instructions', 'allowed_response_type')) {
                $table->string('allowed_response_type')->default('both')->after('deadline');
            }

            // Cek dan tambah kolom class_type jika belum ada
            if (!Schema::hasColumn('course_instructions', 'class_type')) {
                $table->string('class_type')->default('both')->after('allowed_response_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_instructions', function (Blueprint $table) {
            // Drop kolom dalam urutan terbalik
            $columnsToDrop = [
                'class_type',
                'allowed_response_type', 
                'deadline',
                'is_task',
                'file_path',
                'description',
                'title',
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('course_instructions', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Drop foreign keys dan kolom
            if (Schema::hasColumn('course_instructions', 'instructor_id')) {
                $table->dropForeign(['instructor_id']);
                $table->dropColumn('instructor_id');
            }

            if (Schema::hasColumn('course_instructions', 'training_year_id')) {
                $table->dropForeign(['training_year_id']);
                $table->dropColumn('training_year_id');
            }
        });
    }
};
