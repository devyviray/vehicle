<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trucker extends Model implements Auditable
{
    protected $connection  = 'sqlsrv';
    protected $dateFormat = ' Y-m-d H:i:s';
    
    use SoftDeletes,\OwenIt\Auditing\Auditable;
}
