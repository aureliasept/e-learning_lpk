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
        Schema::table('modules', function (Blueprint $table) {
            // Changed: Use training_year_id instead of training_batch_id
            $table->foreignId('training_year_id')->nullable()->after('course_id')->constrained('training_years')->onDelete('set null');
            $table->enum('class_type', ['reguler', 'karyawan', 'both'])->default('both')->after('file_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign(['training_year_id']);
            $table->dropColumn(['training_year_id', 'class_type']);
        });
    }
};
