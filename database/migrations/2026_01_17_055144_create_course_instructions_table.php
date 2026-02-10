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
        // Hanya buat tabel jika belum ada
        if (!Schema::hasTable('course_instructions')) {
            Schema::create('course_instructions', function (Blueprint $table) {
                $table->id();
                // Changed: Use training_year_id instead of training_batch_id
                $table->foreignId('training_year_id')->nullable()->constrained('training_years')->onDelete('cascade');
                $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('file_path')->nullable();
                $table->boolean('is_task')->default(false);
                $table->datetime('deadline')->nullable();
                $table->string('allowed_response_type')->default('both');
                $table->string('class_type')->default('both');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_instructions');
    }
};
