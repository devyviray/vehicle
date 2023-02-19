<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PlantVehicleDeleted extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $connection  = 'sqlsrv';
    protected $table = 'plant_vehicle_deleteds';
    protected $fillable = [
        'plant_vehicle_id',
        'plant_id',
        'vehicle_id',
        'user_id'
    ];
    public function getDateFormat()
    {
        return str_replace(['.v', '.u'], '.000', parent::getDateFormat());
    }
}
