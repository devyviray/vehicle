<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PlantVehicleDeleted extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $connection  = 'sqlsrv';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $table = 'plant_vehicle_deleteds';
    protected $fillable = [
        'plant_vehicle_id',
        'plant_id',
        'vehicle_id',
        'user_id'
    ];
    public function getDates()
    {
        return [];
    }
}
