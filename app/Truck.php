<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $connection = "sqlsrv2";
    protected $table = "trucks";

}
