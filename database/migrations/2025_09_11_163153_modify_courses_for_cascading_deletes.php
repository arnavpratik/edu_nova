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
        Schema::table('courses', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign('courses_teacher_id_foreign');

            // Add the new foreign key constraint with cascading delete
            $table->foreign('teacher_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // This is the reverse operation, to be safe
            $table->dropForeign(['teacher_id']);
            $table->foreign('teacher_id')->references('id')->on('users');
        });
    }
};