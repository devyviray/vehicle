<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driverversions extends Model
{
    protected $connection = "sqlsrv2";
    protected $table = "driverversions";

    public function drivers_info(){
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }


}
