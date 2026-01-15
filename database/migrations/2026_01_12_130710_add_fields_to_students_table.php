<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'academic_year_id')) {
                $table->foreignId('academic_year_id')
                    ->nullable()
                    ->index()
                    ->after('user_id')
                    ->constrained('academic_years')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('students', 'entry_date')) {
                $table->date('entry_date')->nullable()->after('classroom');
            }

            if (!Schema::hasColumn('students', 'gender')) {
                $table->string('gender', 20)->nullable()->after('entry_date');
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
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'academic_year_id')) {
                $table->dropConstrainedForeignId('academic_year_id');
            }

            $columns = [];

            if (Schema::hasColumn('students', 'entry_date')) {
                $columns[] = 'entry_date';
            }
            if (Schema::hasColumn('students', 'gender')) {
                $columns[] = 'gender';
            }
            if (Schema::hasColumn('students', 'address')) {
                $columns[] = 'address';
            }
            if (Schema::hasColumn('students', 'birth_place')) {
                $columns[] = 'birth_place';
            }
            if (Schema::hasColumn('students', 'birth_date')) {
                $columns[] = 'birth_date';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
