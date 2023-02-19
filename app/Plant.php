<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Plant extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $connection  = 'sqlsrv';
    protected $dateFormat = 'Y-m-d H:i:s';
    public function getDates()
    {
        return [];
    }
}
