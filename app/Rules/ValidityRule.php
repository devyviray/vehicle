<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\{
    Vehicle
};

class ValidityRule implements Rule
{

    protected $validityStartDate;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($validityStartDate)
    {
        $this->validityStartDate = $validityStartDate;
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
        $vehicle = Vehicle::where('plate_number', $value)->first();

        if($vehicle && $this->validityStartDate){
            if($vehicle->validity_end_date >=  $this->validityStartDate){
                return false;
            }else{
                return true;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Cannot add  the same truck with the early start date';
    }
}
