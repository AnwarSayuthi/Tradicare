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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('order_id')->nullable()->constrained('orders', 'order_id');
            $table->foreignId('appointment_id')->nullable()->constrained('appointments', 'appointment_id');
            $table->decimal('amount', 10, 2);
            $table->timestamp('payment_date');
            $table->string('payment_method');
            $table->enum('status', ['pending', 'completed', 'failed']);
            $table->string('transaction_id')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
