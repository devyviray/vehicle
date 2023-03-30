<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlantVehicle extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,SoftDeletes;

    protected $table = 'plant_vehicle';
}
