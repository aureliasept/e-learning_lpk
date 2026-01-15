<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('access_code', 6)->nullable()->after('is_active');
            $table->boolean('show_answers_after')->default(false)->after('access_code');
            $table->boolean('shuffle_questions')->default(false)->after('show_answers_after');
            $table->boolean('shuffle_options')->default(false)->after('shuffle_questions');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['access_code', 'show_answers_after', 'shuffle_questions', 'shuffle_options']);
        });
    }
};
