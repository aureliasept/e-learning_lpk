<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek dulu, jika tabel sudah ada, hapus dulu biar bersih (aman untuk tahap development)
        Schema::dropIfExists('courses');

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            
            // KOLOM LEVEL (Wajib ada agar tidak error "Unknown column level")
            $table->enum('level', ['pemula', 'menengah', 'mahir'])->default('pemula');
            
            // KOLOM PRICE SUDAH KITA HAPUS
            
            $table->string('cover_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};