<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vehicle;

use Carbon\Carbon;

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

    $expiration_date = Carbon::now();

    $plate_number = isset($request->plate_number) ? $request->plate_number : "";

    $vehicle = Vehicle::orderBy('validity_start_date', 'desc');

    if($plate_number){
        $vehicle->where('plate_number',$plate_number)
                        ->whereDate('validity_end_date','>',$expiration_date);
    }

    $vehicle = $vehicle->first();

    if($vehicle){
        return 'Valid';
    }else{
        return 'Expired';
    }
   }

   public function getPlateNumbers($plateNumber){
    return Vehicle::with('vendor','capacity')
            ->where('plate_number',$plateNumber)
            ->orderBy('validity_end_date','desc')
            ->limit(1)
            ->get()
            ->map(function ($item) {
                return [
                    'plate_number' => $item->plate_number,
                    'validity_start_date' => $item->validity_start_date,
                    'validity_end_date' => $item->validity_end_date,
                    'capacity_id' => $item->capacity_id,
                    'capacity' => $item->capacity->description,
                    'vendor_codes' => [
                        'LFUG' => $item->vendor->vendor_code_lfug,
                        'CSCI' => $item->vendor->vendor_code_pfmc,
                        'HANA' => $item->vendor->vendor_code_hana,
                    ]
                ];
            });
    }
}
