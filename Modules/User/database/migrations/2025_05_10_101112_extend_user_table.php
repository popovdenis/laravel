<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->time('preferred_start_time')->after('timeZoneName')->nullable();
            $table->time('preferred_end_time')->after('preferred_start_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('dstOffset');
            $table->dropColumn('rawOffset');
            $table->dropColumn('timeZoneId');
            $table->dropColumn('timeZoneName');
        });
    }
};
