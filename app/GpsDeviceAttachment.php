<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GpsDeviceAttachment extends Model
{
    protected $connection  = 'sqlsrv';
    protected $dateFormat = 'M j Y h:i:s:000A';
}
