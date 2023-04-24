<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\{
    Vehicle
};

class ValidityRule implements Rule
{

    protected $validityStartDate;
    protected $action;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($validityStartDate, $action, $id = null)
    {
        $this->validityStartDate = $validityStartDate;
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
        
        if($vehicles && $this->validityStartDate){
            foreach($vehicles as $vehicle){ 
                if($vehicle->validity_end_date >=  $this->validityStartDate){
                    $error = $error + 1;
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
        return 'Previous plate number is not yet ended';
    }
}
