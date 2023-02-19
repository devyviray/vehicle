<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Role extends Model
{
    protected $connection  = 'sqlsrv';
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
