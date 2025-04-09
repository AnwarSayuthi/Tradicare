<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id'); // Primary Key
            $table->foreignId('user_id') // Foreign Key
                  ->constrained('users') // References the 'id' on 'users' table
                  ->onDelete('cascade'); // Deletes appointments if user is deleted
            $table->dateTime('appointment_date');
            $table->enum('status', ['active', 'not'])->default('active');
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
