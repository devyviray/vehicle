<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driverversions extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s'; 
    protected $connection = "sqlsrv2";
    protected $table = "driverversions";

    public function drivers_info(){
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }


}
