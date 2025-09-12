<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->integer('score'); // e.g., 80 for 80%
            $table->json('results_data'); // To store detailed answers
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};