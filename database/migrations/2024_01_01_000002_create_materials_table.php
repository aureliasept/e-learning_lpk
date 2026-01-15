<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            // Terhubung ke Modul (Course)
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            
            // Kolom File & Deskripsi
            $table->string('file_path')->nullable(); 
            $table->text('description')->nullable();
            
            $table->text('content')->nullable(); 
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};