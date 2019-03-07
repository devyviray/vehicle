<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plate_number', 255);
            $table->bigInteger('category_id');
            $table->bigInteger('capacity_id');
            $table->bigInteger('indicator_id');
            $table->string('good_id',191);
            $table->bigInteger('allowed_total_weight');
            $table->string('remarks',255);
            $table->bigInteger('based_truck_id');
            $table->string('contract_id',191);
            $table->bigInteger('document_id');
            $table->bigInteger('user_id');
            $table->dateTime('validity_start_date');
            $table->dateTime('validity_end_date');
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
        Schema::dropIfExists('vehicles');
    }
}
