<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('product_name');
            $table->decimal('price', 10, 2);
            $table->text('description');
            $table->integer('stock_quantity');
            $table->string('category');
            $table->string('product_image')->nullable();
            $table->boolean('active')->default(true);  // Added active column
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};