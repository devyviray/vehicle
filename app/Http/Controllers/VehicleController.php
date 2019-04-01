<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    Vehicle,
    Document,
    PlantVehicle,
    PlantVehicleDeleted
};
use App\Rules\ValidityRule;
use Carbon;
use Auth;
use Storage;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user','vendor', 'subconVendor','plants')->orderBy('id', 'desc')->get();
        return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user','vendor', 'subconVendor')->orderBy('id', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->category_id == 2){
            $request->validate([
                'plate_number' => ['required', 'max:20','regex:/^[\s0-9A-Za-z]+$/', new ValidityRule($request->validity_start_date,'Add')],
                'category_id' => 'required',
                'capacity_id' => 'required',
                'vendor_id' => 'required',
                'allowed_total_weight' => 'integer',
                'subcon_vendor_id' => 'required_if:vendor_id,==,26',
                'contract_id' => 'required_if:vendor_id,==,26',
                'indicator_id' => 'required',
                'remarks' => 'max:40',
                'based_truck_id' => 'required',
                'validity_start_date' => 'required',
                'validity_end_date' => 'required|after_or_equal:validity_start_date',
                'attachments' => 'required',
                'plants' => 'required',
            ]);
        }else{
            $request->validate([
                'plate_number' => ['required','max:8','regex:/^[\s0-9A-Za-z]+$/', new ValidityRule($request->validity_start_date, 'Add')],
                'category_id' => 'required',
                'capacity_id' => 'required',
                'vendor_id' => 'required',
                'allowed_total_weight' => 'integer',
                'subcon_vendor_id' => 'required_if:vendor_id,==,26',
                'contract_id' => 'required_if:vendor_id,==,26',
                'indicator_id' => 'required',
                'remarks' => 'max:40',
                'based_truck_id' => 'required',
                'validity_start_date' => 'required',
                'validity_end_date' => 'required|after_or_equal:validity_start_date',
                'attachments' => 'required',
                'plants' => 'required',
            ]);
        }
        if($vehicle = Vehicle::create(['user_id' => Auth::user()->id] + $request->all())){
            $attachments = $request->file('attachments');   
            foreach($attachments as $attachment){
                $filename = $attachment->getClientOriginalName();
                $path = Storage::disk('public')->put('document', $attachment);
                // $path = $attachment->store('document');

                $uploadedFile = $this->uploadFiles($vehicle->id, $path, $filename);
            }
             
            $vehicle->plants()->sync(explode(",",$request->plants));

            return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants')->where('id', $vehicle->id)->first();
        }
    }

    /**
     * Uploading files for ccir
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadFiles($vehicleId,$path, $filename)
    {
        $document = new Document;
        $document->vehicle_id = $vehicleId;
        $document->path = $path;
        $document->file_name = $filename;
        $document->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user','vendor', 'subconVendor','plants')
            ->where('id',$id)->orderBy('id', 'desc')->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        if($request->category_id == 2){
            $request->validate([
                'plate_number' =>  ['required','max:20','regex:/^[\s0-9A-Za-z]+$/', new ValidityRule($request->validity_start_date, 'Edit',$vehicle->id)],
                'category_id' => 'required',
                'capacity_id' => 'required',
                'vendor_id' => 'required',
                'allowed_total_weight' => 'integer',
                'subcon_vendor_id' => 'required_if:vendor_id,==,26',
                'contract_id' => 'required_if:vendor_id,==,26',
                'indicator_id' => 'required',
                'remarks' => 'max:40',
                'based_truck_id' => 'required',
                'validity_start_date' => 'required',
                'validity_end_date' => 'required|after_or_equal:validity_start_date',
                'plants' => 'required',
            ]);
        }else{
            $request->validate([
                'plate_number' => ['required','max:8','regex:/^[\s0-9A-Za-z]+$/', new ValidityRule($request->validity_start_date, 'Edit',$vehicle->id)],
                'category_id' => 'required',
                'capacity_id' => 'required',
                'vendor_id' => 'required',
                'allowed_total_weight' => 'integer',
                'subcon_vendor_id' => 'required_if:vendor_id,==,26',
                'contract_id' => 'required_if:vendor_id,==,26',
                'indicator_id' => 'required',
                'remarks' => 'max:40',
                'based_truck_id' => 'required',
                'validity_start_date' => 'required',
                'validity_end_date' => 'required|after_or_equal:validity_start_date',
                'plants' => 'required',
            ]);
        }
        if($vehicle->update(['user_id' => Auth::user()->id] + $request->all())){
            if($request->has('attachments')){
                $attachments = $request->file('attachments');   
                foreach($attachments as $attachment){
                    $filename = $attachment->getClientOriginalName();
                    $path = Storage::disk('public')->put('document', $attachment);
                    // $path = $attachment->store('document');
    
                    $uploadedFile = $this->uploadFiles($vehicle->id, $path, $filename);
                }
            }
            $plantVehicles = PlantVehicle::where('vehicle_id', $vehicle->id)->whereNotIn('plant_id', explode(",",$request->plants))->get();
            
            foreach($plantVehicles as $plantVehicle){

                PlantVehicleDeleted::create(['plant_vehicle_id' => $plantVehicle['id'], 
                    'plant_id' => $plantVehicle['plant_id'], 
                    'vehicle_id'=> $plantVehicle['vehicle_id'],
                    'user_id' => Auth::user()->id
                ]);
            }

            $vehicle->plants()->sync(explode(",",$request->plants));

            return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants')->where('id', $vehicle->id)->first();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
        if($vehicle->delete()){
            return $vehicle;
        }
    }
}
