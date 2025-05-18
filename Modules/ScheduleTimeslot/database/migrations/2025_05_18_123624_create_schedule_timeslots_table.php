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
        Schema::table('schedule_timeslots', function (Blueprint $table) {
            $table->renameColumn('day', 'day_of_week');
            $table->renameColumn('start', 'start_time');
            $table->renameColumn('end', 'end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_timeslots', function (Blueprint $table) {
            $table->renameColumn('day_of_week', 'day');
            $table->renameColumn('start_time', 'start');
            $table->renameColumn('end', 'end');
        });
    }
};
