<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laravel_fulltext', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('indexable_id');
            $table->string('indexable_type');
            $table->text('indexed_title');
            $table->text('indexed_content');
            $table->unique(['indexable_type', 'indexable_id']);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE laravel_fulltext ADD FULLTEXT fulltext_title(indexed_title)');
        DB::statement('ALTER TABLE laravel_fulltext ADD FULLTEXT fulltext_title_content(indexed_title, indexed_content)');
    }

    public function down(): void
    {
        Schema::dropIfExists('laravel_fulltext');
    }
};
