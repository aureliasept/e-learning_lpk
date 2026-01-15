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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            // Menghubungkan Bab ke Modul (Induknya)
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            
            $table->string('title'); // Judul Bab (Misal: Bab 1)
            $table->text('content')->nullable(); // Isi Materi Teks
            $table->string('file_path')->nullable(); // File PDF/PPT
            $table->string('video_url')->nullable(); // Link Video Youtube
            $table->boolean('is_active')->default(true); // Status Aktif/Tidak
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};