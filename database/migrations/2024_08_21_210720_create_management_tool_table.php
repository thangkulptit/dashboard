<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagementToolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_tool', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('game_id');
            $table->string('mac_address')->nullable();
            $table->string('license_key')->nullable();
            $table->string('total_devices')->default(1);
            $table->string('active')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('management_tool');
    }
}
