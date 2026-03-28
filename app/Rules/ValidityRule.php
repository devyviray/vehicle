<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\{
    Trucker,
    Vehicle
};
use Illuminate\Support\Facades\Log;

class ValidityRule implements Rule
{

    protected $formStartDate;
    protected $formEndDate;
    protected $action;
    protected $id;
    // protected $message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($validityStartDate, $action, $id = null, $validityEndDate)
    {
        $this->formStartDate = $validityStartDate;
        $this->action = $action;
        $this->id = $id;
        $this->formEndDate = $validityEndDate;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $overlapping = Vehicle::where('plate_number', $value)
            ->where('id','!=',$this->id)
            ->where(function ($query) {
                $query->whereDate('validity_start_date', '<=', $this->formEndDate)
                    ->whereDate('validity_end_date', '>=', $this->formStartDate);
            })
            ->first();

        return $overlapping ? false : true;

        // $vehicles = $this->action == 'Add' ? Vehicle::where('plate_number', $value)->get() : Vehicle::where('plate_number', $value)->where('id','!=',$this->id)->get();
        // $error = 0;
        // if($vehicles && $this->formStartDate){
        //     foreach($vehicles as $vehicle){ 
        //         if($this->action == 'Add'){
        //             $date_end = $vehicle->validity_end_date;
        //             $date = trim($date_end, "12:00:00:AM");
        //             $end_date = date('Y-m-d',strtotime($date));
        //             if($end_date >= $this->formStartDate){
        //                 $this->message = 'Previous plate number is not yet ended';
        //                 $error = $error + 1;
        //             }
        //         } else { // for edit
        //             $date_end = $vehicle->validity_end_date;
        //             $end_date = date('Y-m-d',strtotime($date_end));
        //             $date_start = $vehicle->validity_start_date;
        //             $start_date = date('Y-m-d',strtotime($date_start));
        //             if($this->formStartDate <= $start_date && $this->formEndDate >= $start_date){
        //                 $this->message = 'Plate number already exists with overlapping validity dates';
        //                 $error = $error + 1;
        //             }
        //         }
        //     }
        // }
        // if($error){
        //     return false;
        // }else{
        //     return true; 
        // }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Plate number already exists with overlapping validity dates';
    }
}
