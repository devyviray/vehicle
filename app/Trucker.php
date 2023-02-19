<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class Trucker extends Model implements Auditable
{ 
    use SoftDeletes,\OwenIt\Auditing\Auditable;

    protected $connection  = 'sqlsrv';
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
