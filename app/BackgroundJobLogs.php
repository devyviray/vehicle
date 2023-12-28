<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BackgroundJobLogs extends Model
{
    use SoftDeletes;

    protected $table = "background_job_logs";
    protected $dateFormat = 'Y-m-d H:i:s'; 

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];
}
