<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GpsDeviceAttachment extends Model
{
    protected $connection  = 'sqlsrv';
    public function getDateFormat()
    {
        return str_replace(['.v', '.u'], '.000', parent::getDateFormat());
    }
}
