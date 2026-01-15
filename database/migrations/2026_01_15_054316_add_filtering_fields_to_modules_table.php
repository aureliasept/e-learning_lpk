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
            $table->foreignId('training_batch_id')->nullable()->after('course_id')->constrained('training_batches')->onDelete('set null');
            $table->enum('class_type', ['reguler', 'karyawan', 'both'])->default('both')->after('file_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign(['training_batch_id']);
            $table->dropColumn(['training_batch_id', 'class_type']);
        });
    }
};
