<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverTruck extends Model
{
    protected $connection = "sqlsrv2";
    protected $table = "driver_truck";

    public function drivers_info(){
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }

    public function trucks_info(){
        return $this->hasOne(Truck::class, 'id', 'truck_id');
    }
}
