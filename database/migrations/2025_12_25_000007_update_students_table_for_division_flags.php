<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('students')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'nis')) {
                $table->string('nis')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('students', 'is_reguler')) {
                $table->boolean('is_reguler')->default(false)->after('nis');
            }

            if (! Schema::hasColumn('students', 'is_karyawan')) {
                $table->boolean('is_karyawan')->default(false)->after('is_reguler');
            }

            if (! Schema::hasColumn('students', 'phone')) {
                $table->string('phone')->nullable()->after('is_karyawan');
            }

            if (! Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
        });

        if (Schema::hasColumn('students', 'nis')) {
            try {
                DB::statement('ALTER TABLE students MODIFY nis VARCHAR(255) NULL');
            } catch (\Throwable $e) {
                // ignore; environment might not support ALTER
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('students')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('students', 'is_karyawan')) {
                $table->dropColumn('is_karyawan');
            }
            if (Schema::hasColumn('students', 'is_reguler')) {
                $table->dropColumn('is_reguler');
            }
        });

        if (Schema::hasColumn('students', 'nis')) {
            try {
                DB::statement('ALTER TABLE students MODIFY nis VARCHAR(255) NOT NULL');
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }
};
