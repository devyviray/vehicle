<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGpsDeviceAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gps_device_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('vehicle_id');
            $table->bigInteger('gps_device_id');
            $table->string('path');
            $table->string('file_name');
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
        Schema::dropIfExists('gps_device_attachments');
    }
}
