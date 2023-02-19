<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GpsDeviceAttachment extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s';

    public function getDates()
    {
        return [];
    }
}
