<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BasedTruck extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $hidden = [
        'created_at','updated_at'
    ];
}
