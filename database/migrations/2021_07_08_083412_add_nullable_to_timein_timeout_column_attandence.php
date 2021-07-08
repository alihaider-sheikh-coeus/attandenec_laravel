<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableToTimeinTimeoutColumnAttandence extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attandence', function (Blueprint $table) {
            $table->time('time_in')->nullable()->change();
            $table->time('time_out')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attandence', function (Blueprint $table) {
            //
        });
    }
}
