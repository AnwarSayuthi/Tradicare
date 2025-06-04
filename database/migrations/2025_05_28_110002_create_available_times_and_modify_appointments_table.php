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
        // Create available_times table
        Schema::create('available_times', function (Blueprint $table) {
            $table->id('available_time_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });

        // Create unavailable_times table
        Schema::create('unavailable_times', function (Blueprint $table) {
            $table->id('unavailable_time_id');
            $table->foreignId('available_time_id')->constrained('available_times', 'available_time_id');
            $table->timestamps();
        });

        // Modify appointments table
        Schema::table('appointments', function (Blueprint $table) {
            // Drop columns
            $table->dropColumn('appointment_date');
            $table->dropColumn('end_time');
            
            // Add foreign key
            $table->foreignId('available_time_id')->nullable()->constrained('available_times', 'available_time_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore appointments table to original state
        Schema::table('appointments', function (Blueprint $table) {
            // Remove foreign key
            $table->dropForeign(['available_time_id']);
            $table->dropColumn('available_time_id');
            
            // Add back original columns
            $table->dateTime('appointment_date');
            $table->dateTime('end_time');
        });

        // Drop the new tables
        Schema::dropIfExists('unavailable_times');
        Schema::dropIfExists('available_times');
    }
};