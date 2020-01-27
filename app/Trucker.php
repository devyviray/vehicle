<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Trucker extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'vendor_code_lfug',
        'vendor_code_pfmc',
        'vendor_description_lfug',
        'vendor_description_pfmc',
    ];
}
