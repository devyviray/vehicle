<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s'; 
    protected $connection = "sqlsrv2";
    protected $table = "trucks";

    public function drivers(){
        return $this->belongsTo(Driver::class);
    }
}
