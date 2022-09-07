<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trucker;
use App\Vehicle;
class ApiController extends Controller
{
    public function truckers(){
        return $response = Trucker::select('id','vendor_description_lfug')
                                    ->orderBy('vendor_description_lfug','ASC')
                                    ->get();
    }

    public function vehicles(Trucker $trucker){
        $date_now = date('Y-m-d');
        return $response = Vehicle::select('id','plate_number','capacity_id','asc_extended','validity_end_date')
                                    ->with('capacity')
                                    ->where('vendor_id',$trucker->id)
                                    ->where('asc_extended','true')
                                    ->where('validity_end_date','>=',$date_now)
                                    ->orderBy('plate_number','ASC')
                                    ->get();
    }
}
