<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;

class GpsDeviceCheckUp extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'gps_device_id',
        'vehicle_id',
        'check_up_date',
        'remarks',
    ];

}
