<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    Vehicle,
    Document
};
use Carbon;
use Auth;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'document', 'user','vendor', 'subconVendor')->orderBy('id', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required',
            'category_id' => 'required',
            'capacity_id' => 'required',
            'vendor_id' => 'required',
            'subcon_vendor_id' => 'required',
            'indicator_id' => 'required',
            'good_id' => 'required',
            'allowed_total_weight' => 'required',
            'remarks' => 'required',
            'based_truck_id' => 'required',
            'contract_id' => 'required',
            'validity_start_date' => 'required',
            'validity_end_date' => 'required',
            // 'date' => 'required',
            // 'time' => 'required',
            'attachments' => 'required',
            'plants' => 'required',
        ]);
        if($vehicle = Vehicle::create(['user_id' => Auth::user()->id] + $request->all())){
            $attachments = $request->file('attachments');   
            foreach($attachments as $attachment){
                $filename = $attachment->getClientOriginalName();
                $path = $attachment->store('document');

                $uploadedFile = $this->uploadFiles($vehicle->id, $path, $filename);
            }
             
            // $vehicle->plants()->sync( (array) $request->plants);

            return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'document', 'user', 'vendor', 'subconVendor')->where('id', $vehicle->id)->first();
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
        //
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
        $request->validate([
            'plate_number' => 'required',
            'category_id' => 'required',
            'capacity_id' => 'required',
            'vendor_id' => 'required',
            'subcon_vendor_id' => 'required',
            'indicator_id' => 'required',
            'good_id' => 'required',
            'allowed_total_weight' => 'required',
            'remarks' => 'required',
            'based_truck_id' => 'required',
            'contract_id' => 'required',
            'validity_start_date' => 'required',
            'validity_end_date' => 'required',
            // 'date' => 'required',
            // 'time' => 'required'
        ]);
        if($vehicle->update(['user_id' => Auth::user()->id] + $request->all())){
            if($request->has('attachments')){
                $attachments = $request->file('attachments');   
                foreach($attachments as $attachment){
                    $filename = $attachment->getClientOriginalName();
                    $path = $attachment->store('document');
    
                    $uploadedFile = $this->uploadFiles($vehicle->id, $path, $filename);
                }
            }
            return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'document', 'user')->where('id', $vehicle->id)->first();
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
