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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('stripe_id')->unique();
            $table->timestamp('due_date')->nullable();
            $table->decimal('amount_due', 15, 4)->nullable();
            $table->decimal('is_paid', 15, 4)->nullable();
            $table->string('currency')->default('aud');
            $table->string('hosted_url')->nullable();
            $table->string('pdf_url')->nullable();
            $table->string('increment_id')->nullable();
            $table->string('status')->default('draft');
            $table->decimal('subtotal', 15, 4)->nullable();
            $table->decimal('total', 15, 4)->nullable();
            $table->decimal('total_excl_tax', 15, 4)->nullable();
            $table->decimal('tax')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('stripe_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
