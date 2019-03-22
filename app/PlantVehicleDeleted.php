<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlantVehicleDeleted extends Model
{
    protected $table = 'plant_vehicle_deleteds';

    protected $fillable = [
        'plant_vehicle_id',
        'plant_id',
        'vehicle_id',
        'user_id'
    ]; 
}
