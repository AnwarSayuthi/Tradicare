<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id('service_id');
            $table->string('service_name');
            $table->text('description');
            $table->integer('duration_minutes');
            $table->decimal('price', 10, 2);
            $table->string('icon')->nullable()->default('bi-diamond-fill');
            $table->boolean('active')->default(true);
            $table->boolean('deleted')->default(false); // Added deleted field
            $table->timestamps(); // Add timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
