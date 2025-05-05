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
//        Schema::create('cron_schedules', function (Blueprint $table) {
//            $table->id();
//            $table->boolean('enabled')->default(true);
//            $table->string('target_type')->index();
//            $table->string('command')->index();
//            $table->enum('frequency', ['every_minute', 'hourly', 'daily', 'weekly', 'monthly'])->index();
//            $table->tinyInteger('day')->nullable();
//            $table->tinyInteger('day_of_week')->nullable();
//            $table->tinyInteger('hours')->nullable();
//            $table->tinyInteger('minutes')->nullable();
//            $table->string('description')->nullable();
//
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        Schema::dropIfExists('cron_schedules');
    }
};
