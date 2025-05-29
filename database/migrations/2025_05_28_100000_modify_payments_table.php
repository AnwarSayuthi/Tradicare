<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Add new columns
            $table->string('bill_reference')->nullable();
            
            // Rename bill_code to billcode if it exists
            if (Schema::hasColumn('payments', 'bill_code')) {
                $table->renameColumn('bill_code', 'billcode');
            } else {
                $table->string('billcode')->nullable();
            }
            
            // Add timestamps if they don't exist
            if (!Schema::hasColumn('payments', 'created_at') && !Schema::hasColumn('payments', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revert new columns
            $table->dropColumn('bill_reference');
            
            // Revert billcode to bill_code if it was renamed
            if (Schema::hasColumn('payments', 'billcode')) {
                $table->renameColumn('billcode', 'bill_code');
            }
            
            // Drop timestamps if they were added
            if (Schema::hasColumn('payments', 'created_at') && Schema::hasColumn('payments', 'updated_at')) {
                $table->dropTimestamps();
            }
        });
    }
}