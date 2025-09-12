<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('engagement_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->integer('active_seconds')->default(0);
            $table->integer('idle_seconds')->default(0);
            $table->integer('tab_switches')->default(0);
            $table->date('log_date');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('engagement_logs');
    }
};