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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'place_of_birth')) {
                $table->string('place_of_birth')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('students', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('place_of_birth');
            }
            if (!Schema::hasColumn('students', 'gender')) {
                $table->enum('gender', ['L', 'P'])->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('students', 'entry_date')) {
                $table->date('entry_date')->nullable()->after('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['place_of_birth', 'date_of_birth', 'gender', 'address', 'entry_date']);
        });
    }
};