<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    Vehicle,
    GpsDevice,
    Document,
    PlantVehicle,
    PlantVehicleDeleted,
    PlantVehicleAdded,
    Trucker
};
use App\Rules\ValidityRule;
use Carbon\Carbon;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

use Auth;
use DB;
use Storage;
use Config;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user','vendor', 'subconVendor','gpsdevice','gpsdeviceattachments')
            ->when(Auth::user()->level() < 4, function ($query){
                $query->whereIn('based_truck_id', Auth::user()->basedTrucks->pluck('id'));
            })->orderBy('id', 'desc')->get();
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

        DB::beginTransaction();
        try {

            if($vehicle = Vehicle::create(['user_id' => Auth::user()->id] + $request->all())){
                $attachments = $request->file('attachments');
                if(!empty($attachments)){   
                    foreach($attachments as $attachment){
                        $filename = $attachment->getClientOriginalName();
                        $path = Storage::disk('public')->put('document', $attachment);
                        $uploadedFile = $this->uploadFiles($vehicle->id, $path, $filename);
                    }
                }
                 
                $vehicle->plants()->sync(explode(",",$request->plants));
    
                foreach(explode(",",$request->plants) as $newPlant){                             
                    $plantVehicle = PlantVehicle::where('vehicle_id', $vehicle->id)->where('plant_id', $newPlant)->first();
                    PlantVehicleAdded::create(['plant_vehicle_id' => $plantVehicle->id, 
                        'plant_id' => $newPlant, 
                        'vehicle_id'=> $vehicle->id,
                        'user_id' => Auth::user()->id
                    ]);
                }

                DB::commit();
                return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice','gpsdeviceattachments')->where('id', $vehicle->id)->first();
            }

        } catch (Exception $e) {
            DB::rollBack();
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
        return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user','vendor', 'subconVendor','plants','gpsdevice','gpsdeviceattachments')
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
                'old_plants' => 'required'
            ]);
        }else{
            $request->validate([
                'plate_number' => ['required','max:20','regex:/^[\s0-9A-Za-z]+$/', new ValidityRule($request->validity_start_date, 'Edit',$vehicle->id)],
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
                'old_plants' => 'required'
            ]);
        }
        
        DB::beginTransaction();
        try {
            $vehicle_request_data = $request->all();

            if(isset($request->new_plate_number) && !empty($request->new_plate_number)){
                
                $vehicle_data = Vehicle::with('plants')->where('id',$vehicle->id)->first();
                $new_vehicle_data = [];
                $new_vehicle_data['plate_number'] = $request->new_plate_number;
                $new_vehicle_data['category_id'] = '1';
                $new_vehicle_data['capacity_id'] = $vehicle_data['capacity_id'];
                $new_vehicle_data['vendor_id'] = $vehicle_data['vendor_id'];
                $new_vehicle_data['subcon_vendor_id'] = $vehicle_data['subcon_vendor_id'];
                $new_vehicle_data['indicator_id'] = $vehicle_data['indicator_id'];
                $new_vehicle_data['good_id'] = $vehicle_data['good_id'];
                $new_vehicle_data['allowed_total_weight'] = $vehicle_data['allowed_total_weight'];
                $new_vehicle_data['remarks'] = $vehicle_data['remarks'];
                $new_vehicle_data['based_truck_id'] = $vehicle_data['based_truck_id'];
                $new_vehicle_data['contract_id'] = $vehicle_data['contract_id'];
                $new_vehicle_data['user_id'] = Auth::user()->id;
                $new_vehicle_data['validity_start_date'] = $vehicle_data['validity_start_date'];
                $new_vehicle_data['validity_end_date'] = $vehicle_data['validity_end_date'];
                $new_vehicle_data['gps_device_id'] = $vehicle_data['gps_device_id'];
                $new_vehicle_data['previous_plate_number'] = $vehicle_data['plate_number'];
                unset($new_vehicle_data['new_plate_number']);

                $plant_data_ids = [];
                if($vehicle_data['plants']){
                    $plants = [];
                    foreach($vehicle_data['plants'] as $plant){
                        $plants[] = $plant->id;
                    }
                    $plant_data_ids = implode(",",$plants);
                }

                if($new_vehicle = Vehicle::create($new_vehicle_data)){
                    if($plant_data_ids){
                        $new_vehicle->plants()->sync(explode(",",$plant_data_ids));

                        foreach(explode(",",$plant_data_ids) as $plant_data_id){
                            $plantVehicle = PlantVehicle::where('vehicle_id', $new_vehicle->id)->where('plant_id', $plant_data_id)->first();
                            PlantVehicleAdded::create(['plant_vehicle_id' => $plantVehicle->id, 
                                'plant_id' => $plant_data_id, 
                                'vehicle_id'=> $new_vehicle->id,
                                'user_id' => Auth::user()->id
                            ]);
                        }
                    }
                }
                
                $vehicle_request_data['gps_device_id'] = "";
                $vehicle_request_data['change_plate_number_status'] = "Yes";
            }


            if($vehicle->update(['user_id' => Auth::user()->id] + $vehicle_request_data)){
                    $attachments = $request->file('attachments');
                    if(!empty($attachments)){
                        foreach($attachments as $attachment){
                            $filename = $attachment->getClientOriginalName();
                            $path = Storage::disk('public')->put('document', $attachment);
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
    
                $newPlants =  array_diff(explode(",",$request->plants), explode(",",$request->old_plants));
    
                foreach($newPlants as $newPlant){                             
                    $plantVehicle = PlantVehicle::where('vehicle_id', $vehicle->id)->where('plant_id', $newPlant)->first();
                    PlantVehicleAdded::create(['plant_vehicle_id' => $plantVehicle->id, 
                        'plant_id' => $newPlant, 
                        'vehicle_id'=> $vehicle->id,
                        'user_id' => Auth::user()->id
                    ]);
                }
                
                $vehicle_data =  Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice','gpsdeviceattachments')->where('id', $vehicle->id)->first();

                if(isset($request->new_plate_number) && !empty($request->new_plate_number)){

                    $gps_device = GpsDevice::where('id',$new_vehicle['gps_device_id'])->first();
                   
                    if($gps_device){
                        $data=array();
                        $data['device_id'] = $gps_device->device_id;
                        $data['name'] = $request->new_plate_number;
                        $data['method'] = 'edit';

                        $api_assign_id = $this->update_gps_details($data);
                        if($api_assign_id){
                            DB::commit();
                        }else{
                            DB::rollBack();
                        }   
                    }{
                        DB::commit();
                    }
                    return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice','gpsdeviceattachments')->where('id', $vehicle->id)->first();
                }
                else{
                    DB::commit(); 
                    return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice','gpsdeviceattachments')->where('id', $vehicle->id)->first();
                }
            }

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function filterVehicle(Request $request){
       
        $date_today = Carbon::now()->format('Y-m-d');
        $operator = $request->operator;
        $gps = $request->filter_gps;
        $base_truck_ids = $request->filter_based_trucks;

        return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user','vendor', 'subconVendor','gpsdevice','gpsdeviceattachments')
                            ->when(!empty($operator), function ($query) use($operator,$date_today) {
                                $query->where('validity_end_date',$operator,$date_today);
                            })
                            ->when(!empty($gps),function ($query) use ($gps){
                                if($gps == 'Yes'){
                                    $query->whereNotNull('gps_device_id');
                                }elseif($gps == 'No'){
                                    $query->whereNull('gps_device_id');
                                }
                            })
                            ->when(isset($base_truck_ids), function ($query) use($base_truck_ids){
                                $base_truck_ids_arr = explode(',',$base_truck_ids);
                                $query->whereIn('based_truck_id',$base_truck_ids_arr);  
                            }) 
                            ->orderBy('id', 'desc')->get();
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

    public function getVehicleGps(Request $request){

        $this->validate($request,[
            'plate_number' => 'required'
        ]);

        $vehicle =  Vehicle::with('gpsdevice')->where('plate_number',$request->input('plate_number'))->first();
        $vehicle_data = [];
        if($vehicle){
            $vehicle_data['plate_number'] = $vehicle->plate_number;
            if($vehicle->gpsdevice){
                $vehicle_data['with_gps'] = "Yes";
            }else{
                $vehicle_data['with_gps'] = "No";
            }
        }

        return $vehicle_data;

    }

    private function update_gps_details($data=array()){

        $client = new Client();
        $user_api_hash = Config::get('constants.api.gps_hash');
        $default_headers = Config::get('constants.api.gps_headers');

        $default_params = [
            'name'=> $data['name'],
            'plate_number'=> $data['name'],
            'icon_id'=>'0',
            'fuel_measurement_id'=>'1',
            'tail_length'=>'0',
            'min_moving_speed'=>'1',
            'min_fuel_fillings'=>'1',
            'min_fuel_thefts'=>'1',
            'device_id'=> $data['device_id']
        ];

        try {

            $response = $client->post('http://gpstracker.lafilgroup.com/api/'.$data['method'].'_device?user_api_hash='.$user_api_hash, [
                'headers' => $default_headers,
                'form_params'=>  $default_params    
            ]); 

            $device_data = $response->getBody();
            $device_data = json_decode($device_data);

            return $device_data->id;

        }catch (BadResponseException $ex) {
            $response = $ex->getResponse()->getBody();
            return json_decode($response, true);
        }
    }


    public function trucker_store(Request $request){
        $trucker_data = $request->all();

        $this->validate($request,[
            'vendor_description_lfug' => 'required',
            'vendor_description_pfmc' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if($trucker = Trucker::create($trucker_data)){
                DB::commit();
                return Trucker::all();
            }
        } catch (Exception $e) {
            DB::rollBack();
            return Trucker::all();
        }
    }

    public function trucker_update(Request $request, Trucker $trucker){
        $trucker_data = $request->all();

        $this->validate($request,[
            'vendor_description_lfug' => 'required',
            'vendor_description_pfmc' => 'required'
        ]);
       
        DB::beginTransaction();
        try {
            if($trucker->update($trucker_data)){
                DB::commit();
                return Trucker::all();
            }
        } catch (Exception $e) {
            DB::rollBack();
            return Trucker::all();
        }
    }

    public function deleteTrucker(Trucker $trucker){
        if($trucker->delete()){
            return $trucker;
        }
    }

    public function get_sap_lfug(){

        $client = new Client();
        $connection = Config::get('constants.sap_api.connection_lfug');
        $table = Config::get('constants.sap_api.table');

        $response = $client->request('GET', 'http://10.96.4.39:8012/api/read-table', 
                        ['query' => ['connection' => $connection,'table' => $table]]);
        
        return json_decode($response->getBody(), true);
    }

    public function get_sap_pfmc(){

        $client = new Client();
        $connection = Config::get('constants.sap_api.connection_pfmc');
        $table = Config::get('constants.sap_api.table');

        $response = $client->request('GET', 'http://10.96.4.39:8012/api/read-table', 
                        ['query' => ['connection' => $connection,'table' => $table]]);
        
        return json_decode($response->getBody(), true);
    }

}
