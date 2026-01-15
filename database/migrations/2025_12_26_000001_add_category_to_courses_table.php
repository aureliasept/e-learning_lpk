<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'category')) {
                return;
            }

            if (Schema::hasColumn('courses', 'description')) {
                $table->enum('category', ['reguler', 'karyawan'])->default('reguler')->after('description');
                return;
            }

            if (Schema::hasColumn('courses', 'deskripsi')) {
                $table->enum('category', ['reguler', 'karyawan'])->default('reguler')->after('deskripsi');
                return;
            }

            $table->enum('category', ['reguler', 'karyawan'])->default('reguler');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
