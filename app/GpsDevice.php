<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GpsDevice extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $connection  = 'sqlsrv';
    protected $dateFormat = ' Y-m-d H:i:s';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getDates(){
        return [];
    }
    
    protected $fillable = [
        'vehicle_id',
        'imei',
        'sim_number',
        'device_id'
    ];

}
