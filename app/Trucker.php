<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trucker extends Model implements Auditable
{ 
    use SoftDeletes,\OwenIt\Auditing\Auditable;

    protected $connection  = 'sqlsrv';
    public function getDateFormat()
    {
        return str_replace(['.v', '.u'], '.000', parent::getDateFormat());
    }
}
