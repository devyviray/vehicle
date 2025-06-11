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

    protected $formDate;
    protected $action;
    protected $id;
    protected $message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($validityStartDate, $action, $id = null)
    {
        $this->formDate = $validityStartDate;
        $this->action = $action;
        $this->id = $id;
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
        $vehicles = $this->action == 'Add' ? Vehicle::where('plate_number', $value)->get() : Vehicle::where('plate_number', $value)->where('id','!=',$this->id)->get();
        $error = 0;
        if($vehicles && $this->formDate){
            foreach($vehicles as $vehicle){ 
                $date_end = $vehicle->validity_end_date;
                $date = trim($date_end, "12:00:00:AM");
                $end_date = date('Y-m-d',strtotime($date));
                if($this->action == 'Add'){
                    if($end_date >= $this->formDate){
                        $this->message = 'Previous plate number is not yet ended';
                        $error = $error + 1;
                    }
                } else { // for edit
                    $date_start = $vehicle->validity_start_date;
                    $date = trim($date_start, "12:00:00:AM");
                    $start_date = date('Y-m-d',strtotime($date));
                    if($this->formDate >= $start_date && $this->formDate <= $end_date){
                        $this->message = 'Plate number already exists with overlapping validity dates';
                        $error = $error + 1;
                    }
                }
            }
        }
        if($error){
            return false;
        }else{
            return true; 
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
