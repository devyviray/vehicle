<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s';

    use \OwenIt\Auditing\Auditable;

    public function getDates()
    {
        return [];
    }
}
