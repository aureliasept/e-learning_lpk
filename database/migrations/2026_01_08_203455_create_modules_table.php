<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul
            $table->string('subtitle')->nullable(); 
            $table->text('description')->nullable();
            $table->string('file_path'); // Lokasi file
            $table->string('file_type')->default('pdf');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('modules');
    }
};