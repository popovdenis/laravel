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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->smallInteger('frequency')->unsigned()->default(1);
            $table->enum('frequency_unit', ['day', 'week', 'month', 'year'])->default('month');
            $table->boolean('enable_trial')->default(true);
            $table->smallInteger('trial_days')->unsigned()->default(0);
            $table->decimal('price', 15, 4)->default('0.00');
            $table->integer('credits')->unsigned()->default(0);
            $table->boolean('enable_initial_fee')->default(false);
            $table->enum('initial_fee_type', ['fixed', 'percent'])->nullable();
            $table->decimal('initial_fee_amount', 15, 4)->default('0.00');

            $table->tinyInteger('sort_order')->nullable();
            $table->timestamps();
        });
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('subscription_plans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['active', 'trialing', 'canceled', 'expired'])->default('active');
            $table->boolean('canceled_immediately')->default(false);
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();
        });
        Schema::create('booking_credit_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('credits_amount')->default(0);
            $table->enum('action', ['spend', 'refund', 'adjustment'])->default('spend');
            $table->string('comment')->nullable();

            $table->index(['user_id', 'booking_id'], 'user_booking_index');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('booking_credit_history');
    }
};
