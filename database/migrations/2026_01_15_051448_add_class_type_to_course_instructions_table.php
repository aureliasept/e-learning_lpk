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
        Schema::table('course_instructions', function (Blueprint $table) {
            $table->enum('class_type', ['reguler', 'karyawan', 'both'])->default('both')->after('allowed_response_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_instructions', function (Blueprint $table) {
            $table->dropColumn('class_type');
        });
    }
};
