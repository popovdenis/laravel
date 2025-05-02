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

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->morphs('purchasable');
            $table->enum('status', ['pending', 'processing', 'complete', 'cancelled'])->default('pending');
            $table->enum('state', ['new', 'pending', 'processing', 'complete', 'cancelled'])->default('pending');
            $table->decimal('total_amount', 15, 4)->default('0.00');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
