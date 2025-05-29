<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shipping_address');
            $table->unsignedBigInteger('location_id')->nullable()->after('user_id');
            $table->foreign('location_id')->references('location_id')->on('locations');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_address')->nullable();
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
        });
    }
}