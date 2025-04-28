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
        Schema::create('attempt_request_events', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('request_type')->unsigned()
                ->comment('Type of the event under a security control');
            $table->string('account_reference')->nullable()
                ->comment('An identifier for existing account or another target');
            $table->timestamps();

            $table->index('account_reference', 'PASSWORD_RESET_REQUEST_EVENT_ACCOUNT_REFERENCE');
            $table->index('created_at', 'PASSWORD_RESET_REQUEST_EVENT_CREATED_AT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempt_request_events');
    }
};
