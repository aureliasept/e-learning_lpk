<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID Instruktur
            $table->string('day'); // Senin, Selasa, dst
            $table->time('start_time');
            $table->time('end_time');
            $table->string('subject'); // Mata Pelajaran
            $table->string('class_name'); // Reguler/Karyawan
            $table->string('room')->nullable(); // Ruang Kelas
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};