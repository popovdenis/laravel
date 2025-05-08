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
        Schema::create('booking_grid_flat', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('booking_id')->unique()->comment('ID original booking');
            // student
            $table->unsignedBigInteger('student_id')->index();
            $table->string('student_firstname');
            $table->string('student_lastname');
            // teacher
            $table->unsignedBigInteger('teacher_id')->index();
            $table->string('teacher_firstname');
            $table->string('teacher_lastname');
            // Stream info
            $table->unsignedBigInteger('stream_id')->index();
            $table->string('level_title');
            $table->string('subject_title');
            $table->unsignedTinyInteger('current_subject_number');
            $table->string('subject_category')->nullable();
            // Time
            $table->timestamp('start_time');
            $table->timestamp('end_time');

            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');

            $table->timestamps();

            $table->index(['start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_grid_flat');
    }
};


