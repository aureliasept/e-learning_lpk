<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Kita tambahkan semua kolom biodata yang kurang
            // Pakai nullable() biar aman kalau datanya kosong
            
            if (!Schema::hasColumn('students', 'phone')) {
                $table->string('phone')->nullable()->after('division');
            }
            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('students', 'birth_place')) {
                $table->string('birth_place')->nullable()->after('address');
            }
            if (!Schema::hasColumn('students', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('birth_place');
            }
            if (!Schema::hasColumn('students', 'gender')) {
                $table->string('gender', 10)->nullable()->after('birth_date'); // L/P
            }
            if (!Schema::hasColumn('students', 'enrollment_date')) {
                $table->date('enrollment_date')->nullable()->after('gender');
            }
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 
                'address', 
                'birth_place', 
                'birth_date', 
                'gender', 
                'enrollment_date'
            ]);
        });
    }
};