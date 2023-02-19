<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s';

    public function getDates()
    {
        return [];
    }

    protected $hidden = [
        'created_at','updated_at'
    ];
    
}
