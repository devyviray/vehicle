<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $connection  = 'sqlsrv';
    protected $dateFormat = ' Y-m-d H:i:s';
    protected $dates = [
        'LocalTime',
    ];

    public function getDates(){
        return [];
    }
}
