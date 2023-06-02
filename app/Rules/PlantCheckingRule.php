<?php

namespace App\Rules;
use App\{
    Trucker,
    Plant
};

use Illuminate\Contracts\Validation\Rule;

class PlantCheckingRule implements Rule
{
    protected $plant_ids;
    protected $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($plant_ids)
    {
        $this->plant_ids = explode(",",$plant_ids);
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
        $plant_servers = Plant::whereIn('id',$this->plant_ids)
            ->pluck('company_server')
            ->unique()
            ->toArray();
      
        $vendor = Trucker::findOrFail($value);
        // Loop servers to check if has maintained code
        foreach($plant_servers as $plant_server){
            $this->message = "Cannot assign plant under ".$plant_server. ", No Vendor code maintained in ".$plant_server;
            // Return error if no maintained code in the server
            $vendor_field = 'vendor_code_'.strtolower($plant_server);
            if(!$vendor->$vendor_field){
                return false;
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
        return $this->message;
    }
}
