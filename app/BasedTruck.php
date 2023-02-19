<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BasedTruck extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $connection  = 'sqlsrv';
    protected $table  = 'based_trucks';  
    protected $dateFormat = 'Y-m-d H:i:s';
    public function getDates()
    {
        return [];
    }
}
