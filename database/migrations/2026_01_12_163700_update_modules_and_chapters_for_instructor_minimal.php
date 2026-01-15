<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            if (!Schema::hasColumn('modules', 'course_id')) {
                $table->foreignId('course_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('courses')
                    ->nullOnDelete();
            }
        });

        if (Schema::hasColumn('modules', 'file_path')) {
            DB::statement("ALTER TABLE modules MODIFY COLUMN file_path VARCHAR(255) NULL");
        }

        Schema::table('chapters', function (Blueprint $table) {
            if (!Schema::hasColumn('chapters', 'instruction')) {
                $table->longText('instruction')->nullable()->after('title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            if (Schema::hasColumn('chapters', 'instruction')) {
                $table->dropColumn('instruction');
            }
        });

        if (Schema::hasColumn('modules', 'file_path')) {
            DB::statement("ALTER TABLE modules MODIFY COLUMN file_path VARCHAR(255) NOT NULL");
        }

        Schema::table('modules', function (Blueprint $table) {
            if (Schema::hasColumn('modules', 'course_id')) {
                $table->dropConstrainedForeignId('course_id');
            }
        });
    }
};
