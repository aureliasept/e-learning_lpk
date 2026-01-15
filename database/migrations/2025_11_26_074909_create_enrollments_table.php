<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');          // siswa
            $table->foreignId('course_id')
                  ->constrained('courses')
                  ->onDelete('cascade');          // course yang diikuti
            $table->enum('status', ['aktif', 'selesai', 'batal'])->default('aktif');
            $table->timestamps();

            $table->unique(['user_id', 'course_id']); // satu siswa satu course sekali
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
