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

    public function category(){
        return $this->belongsTo(Category::Class);
    }

    public function capacity(){
        return $this->belongsTo(Capacity::Class);
    }

    public function indicator(){
        return $this->belongsTo(Indicator::Class);
    }

    public function good(){
        return $this->belongsTo(Good::Class);
    }

    public function basedTruck(){
        return $this->belongsTo(BasedTruck::Class);
    }

    public function contract(){
        return $this->belongsTo(Contract::Class);
    }

    public function document(){
        return $this->belongsTo(Document::Class);
    }

    public function user(){
        return $this->belongsTo(User::Class);
    }
}
