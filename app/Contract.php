<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Contract extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $connection  = 'sqlsrv';
    public function getDateFormat()
    {
        return str_replace(['.v', '.u'], '.000', parent::getDateFormat());
    }
}
