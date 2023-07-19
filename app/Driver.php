<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $connection = "sqlsrv2";
    protected $table = "drivers";

    public function trucks(){
        return $this->belongsTo(Truck::class);
    }

    public function hasTrucks(){
        return $this->hasOne(DriverTruck::class, 'driver_id','id');
    }
}
