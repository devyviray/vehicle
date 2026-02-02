<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVendorCodeAmadiColumnsToTruckersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('truckers', function (Blueprint $table) {
            $table->string('vendor_code_amadi')->nullable();
            $table->string('vendor_description_amadi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('truckers', function (Blueprint $table) {
            $table->dropColumn('vendor_code_amadi');
            $table->dropColumn('vendor_description_amadi');
        });
    }
}
