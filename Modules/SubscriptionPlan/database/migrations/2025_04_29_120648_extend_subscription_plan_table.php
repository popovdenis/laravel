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
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->boolean('enable_discount')->default(false);
            $table->enum('discount_type', ['fixed', 'percent'])->nullable();
            $table->decimal('discount_amount', 15, 4)->default('0.00');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('enable_discount');
            $table->dropColumn('discount_type');
            $table->dropColumn('discount_amount');
        });
    }
};
