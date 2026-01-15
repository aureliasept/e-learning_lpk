<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'slug')) {
                $table->string('slug')->nullable()->after('title');
            }

            if (!Schema::hasColumn('news', 'status')) {
                $table->string('status')->default('draft')->after('author_id');
            }

            if (!Schema::hasColumn('news', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'published_at')) {
                $table->dropColumn('published_at');
            }

            if (Schema::hasColumn('news', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('news', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
