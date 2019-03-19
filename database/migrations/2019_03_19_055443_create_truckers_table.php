<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTruckersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('truckers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('vendor_code_lfug');
            $table->string('vendor_code_pfmc');
            $table->string('vendor_description_lfug');
            $table->string('vendor_description_pfmc');
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
        Schema::dropIfExists('truckers');
    }
}
