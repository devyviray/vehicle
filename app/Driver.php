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
}
