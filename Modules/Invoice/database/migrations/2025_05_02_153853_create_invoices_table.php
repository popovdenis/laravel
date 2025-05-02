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
            $table->string('stripe_invoice_id')->unique();
            $table->string('status')->default('draft');
            $table->decimal('amount', 15, 4);
            $table->string('currency')->default('aud');
            $table->string('hosted_url')->nullable();
            $table->string('pdf_url')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('stripe_invoice_id');
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
