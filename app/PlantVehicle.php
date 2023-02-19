<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PlantVehicle extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $connection  = 'sqlsrv';
    protected $table = 'plant_vehicle';
    public function getDateFormat()
    {
        return str_replace(['.v', '.u'], '.000', parent::getDateFormat());
    }
}
