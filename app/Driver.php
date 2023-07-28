<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s'; 
    protected $connection = "sqlsrv2";
    protected $table = "drivers";

    public function trucks(){
        return $this->belongsTo(Truck::class);
    }

    public function hasTrucks(){
        return $this->hasOne(DriverTruck::class, 'driver_id','id');
    }

    public function driverversions(){
        return $this->hasOne(Driverversions::class, 'driver_id','id');
    }
}
