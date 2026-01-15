<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            // Tugas menempel ke Bab (Material), bukan Modul
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('instruction')->nullable();
            $table->string('file_path')->nullable(); // File soal tugas
            $table->dateTime('deadline')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};