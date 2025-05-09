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
            $table->smallInteger('dstOffset')->after('confirmation')->nullable();
            $table->smallInteger('rawOffset')->after('dstOffset')->nullable();
            $table->tinyText('timeZoneId')->after('rawOffset')->nullable();
            $table->tinyText('timeZoneName')->after('timeZoneId')->nullable();
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
