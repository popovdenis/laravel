<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('language_levels', function (Blueprint $table) {
            $table->dropColumn(['level', 'duration', 'price']);
        });
    }

    public function down(): void
    {
        Schema::table('language_levels', function (Blueprint $table) {
            $table->string('level')->nullable();
            $table->integer('duration')->nullable();
            $table->decimal('price', 8, 2)->nullable();
        });
    }
};
