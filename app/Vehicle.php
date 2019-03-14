<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'plate_number',
        'category_id',
        'capacity_id',
        'indicator_id',
        'good_id',
        'allowed_total_weight',
        'remarks',
        'based_truck_id',
        'contract_id',
        'document_id',
        'user_id',
        'validity_start_date',
        'validity_end_date',
        'date',
        'time'
    ];
}
