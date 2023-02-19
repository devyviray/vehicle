<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    
    protected $connection  = 'sqlsrv';
    // protected $dateFormat = 'Y-m-d H:i:s';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getDateFormat()
    {
         return 'Y-m-d H:i:s.u';
    }
}
