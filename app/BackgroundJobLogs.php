<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BackgroundJobLogs extends Model
{
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];
}
