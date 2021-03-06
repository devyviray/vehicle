<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantVehicleDeletedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plant_vehicle_deleteds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('plant_vehicle_id');
            $table->integer('plant_id');
            $table->integer('vehicle_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('plant_vehicle_deleteds');
    }
}
