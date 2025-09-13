<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // 1. Add a 'type' column to store 'text' or 'image'
            $table->string('type')->default('text')->after('quiz_id');

            // 2. Rename 'question_text' to 'content' to store both text and image paths
            $table->renameColumn('question_text', 'content');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->renameColumn('content', 'question_text');
            $table->dropColumn('type');
        });
    }
};