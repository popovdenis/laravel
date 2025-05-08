<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('streams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_level_id')->constrained()->onDelete('cascade'); // (A1–C2)
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->unsignedInteger('current_subject_number')->default(1);
            $table->enum('status', ['planned', 'started', 'paused', 'finished'])->default('planned'); // status
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('repeat')->default(false);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('streams');
    }
};
