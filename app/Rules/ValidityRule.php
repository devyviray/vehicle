<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\{
    Vehicle
};

class ValidityRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value, $parameters = [])
    {
        // $vehicle = Vehicle::where('plate_number', $value)->first();
        print '<prev>';
            print_r( $parameters);
        print '</prev>';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
