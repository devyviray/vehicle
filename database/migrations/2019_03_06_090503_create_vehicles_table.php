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
            $table->string('plate_number');
            $table->bigInteger('category_id');
            $table->bigInteger('capacity_id');
            $table->bigInteger('vendor_id');
            $table->bigInteger('subcon_vendor_id')->nullable();
            $table->bigInteger('indicator_id');
            $table->bigInteger('good_id')->nullable();
            $table->bigInteger('allowed_total_weight')->nullable();
            $table->string('remarks')->nullable();
            $table->bigInteger('based_truck_id');
            $table->bigInteger('contract_id')->nullable();
            $table->bigInteger('user_id');
            $table->dateTime('validity_start_date');
            $table->dateTime('validity_end_date');
            $table->timestamps();
            $table->softDeletes();
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
