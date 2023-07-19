<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $connection = "sqlsrv2";
    protected $table = "trucks";

    public function drivers(){
        return $this->belongsTo(Driver::class);
    }
}
