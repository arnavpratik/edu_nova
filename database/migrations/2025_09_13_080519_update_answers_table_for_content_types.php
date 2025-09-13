<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->string('type')->default('text')->after('question_id');
            $table->renameColumn('answer_text', 'content');
        });
    }

    public function down(): void
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->renameColumn('content', 'answer_text');
            $table->dropColumn('type');
        });
    }
};