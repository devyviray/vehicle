<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trucker extends Model implements Auditable
{
    protected $connection  = 'sqlsrv';
    protected $dateFormat = ' Y-m-d H:i:s';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getDates(){
        return [];
    }
    
    use SoftDeletes,\OwenIt\Auditing\Auditable;
}
