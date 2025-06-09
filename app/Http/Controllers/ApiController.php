<?php

namespace App\Http\Controllers;

use App\Trucker;
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
    return Vehicle::with('vendor','capacity','gpsdevice')
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
                    ],
                    'gps_device' => $item->gpsDevice
                    ? [
                        'imei' => $item->gpsDevice->imei ?? null,
                        'mobile_number' => $item->gpsDevice->sim_number ?? null,
                        'gps_tracker_id' => $item->gpsDevice->device_id,
                    ]
                    : []
                ];
            })
            ;
    }

   public function getVendorPlateNumbers($s4_vendor_code){
        $trucker = Trucker::where('vendor_code_hana', $s4_vendor_code)->first();
        if($trucker){
            return Vehicle::with('vendor','capacity','gpsdevice','plants')
                ->where(function ($query) use ($trucker){
                    $query->where('vendor_id', $trucker->id)
                    ->orWhere('subcon_vendor_id', $trucker->id);
                })
                ->where('validity_end_date', '>=', Carbon::today())
                ->orderBy('id','desc')
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
                        ],
                        'gps_device' => $item->gpsDevice
                        ? [
                            'imei' => $item->gpsDevice->imei ?? null,
                            'mobile_number' => $item->gpsDevice->sim_number ?? null,
                            'gps_tracker_id' => $item->gpsDevice->device_id,
                        ]
                        : [],
                        'plants_assigned' => $item->plants ? [
                            $item->plants->map(function ($plant) {
                                return [
                                    'id' => $plant->id,
                                    'name' => $plant->name, 
                                    'code' => $plant->code, 
                                    'server' => $plant->company_server, 
                                ];
                            })
                        ] : [],
                    ];
                });
        } else {
            return "No vendor(".$s4_vendor_code. ") found.";
        }

    }
}
