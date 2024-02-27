<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vehicle;

class ApiController extends Controller
{
   public function plateNumberVendorCode(Request $request){
    $vehicle = Vehicle::with('vendor')
                    ->orderBy('id', 'desc');
    if($request->plate_number){
        $vehicle->where('plate_number',$request->plate_number);
    }

    return $vehicle->get();
   }

   public function plateNumberStatus(Request $request){

    $plate_number = isset($request->plate_number) ? $request->plate_number : "";

    $vehicle = Vehicle::orderBy('validity_start_date', 'desc');

    if($plate_number){
        $vehicle->where('plate_number',$plate_number);
    }

    $vehicle = $vehicle->first();
    $vehicle->validity_end_date;

    if($vehicle){
        $date_today = date('Y-m-d');
        if(date('Y-m-d',strtotime($vehicle->validity_end_date)) < $date_today) {
            return 'Expired';
        }else{
            return "Valid";
        }
    }
   }
}
