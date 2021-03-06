<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PlantVehicle extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'plant_vehicle';
}
