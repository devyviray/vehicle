<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class GpsDevice extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $dateFormat = 'Y-m-d H:i:s';

    use SoftDeletes;

    public function getDates()
    {
        return [];
    }
    
    protected $fillable = [
        'vehicle_id',
        'imei',
        'sim_number',
        'device_id'
    ];

}
