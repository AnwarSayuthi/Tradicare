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
        // Add order_id to carts table
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->after('status')
                  ->constrained('orders', 'order_id')
                  ->nullOnDelete();
        });
        
        // Remove cart_id from orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['cart_id']);
            $table->dropColumn('cart_id');
        });
        
        // Drop order_items table
        Schema::dropIfExists('order_items');
        
        // Add payment_details column to payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->json('payment_details')->nullable()->after('transaction_id');
            $table->string('bill_code')->nullable()->after('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove order_id from carts table
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });
        
        // Add cart_id back to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('cart_id')->nullable()->after('user_id')
                  ->constrained('carts', 'cart_id')
                  ->nullOnDelete();
        });
        
        // Recreate order_items table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
        });
        
        // Remove new columns from payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_details');
            $table->dropColumn('bill_code');
        });
    }
};