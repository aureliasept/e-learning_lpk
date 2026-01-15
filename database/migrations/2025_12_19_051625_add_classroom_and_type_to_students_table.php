<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Gunakan hasColumn untuk mengecek keberadaan kolom sebelum menambahkannya
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'classroom')) {
                $table->string('classroom')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('students', 'type')) {
                $table->string('type')->default('reguler')->after('classroom');
            }
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['classroom', 'type']);
        });
    }
};