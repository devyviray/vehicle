<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    GpsDevice,
    Vehicle,
    GpsDeviceAttachment,
    GpsDeviceCheckUp,
};
use DB;
use Storage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

use Config;



class GpsDevicesController extends Controller
{

    public function index(){
        return GpsDevice::orderBy('id','desc')->get();
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
            'imei' => 'required|unique:gps_devices,imei',
            'sim_number' => 'required|unique:gps_devices,sim_number'
        ]);
        DB::beginTransaction();
        try {
            
            if($gps_device = GpsDevice::create($request->all())){

                $attachments = $request->file('attachments');  
                
                if(!empty($attachments)){
                    foreach($attachments as $attachment){
                        $filename = $attachment->getClientOriginalName();
                        $path = Storage::disk('public')->put('gps_attachments', $attachment);
                        $uploadedFile = $this->uploadFiles($gps_device->id,$request->vehicle_id, $path, $filename);
                    }    
                }

                Vehicle::whereId($request->vehicle_id)->update(['gps_device_id' => $gps_device->id]);
        
                $vehicle = Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice','gpsdeviceattachments')->where('id', $request->vehicle_id)->first();
        
                $data=array();
                $data['gps_device_id'] = $gps_device->id;
                $data['name'] = $vehicle->plate_number;
                $data['imei'] = $request->imei;
                $data['sim_number'] = $request->sim_number;
                $data['method'] = 'add';
                
                $api_assign_id = $this->send_api_assign_gps($data);

                if($api_assign_id){
                    GpsDevice::whereId($gps_device->id)->update(['device_id' => $api_assign_id]);
                    DB::commit();
                }else{
                    DB::rollBack();
                }

                return $vehicle;
            }

        } catch (HttpException $ex) {
            DB::rollBack();
            return $vehicle;
        }

    }

    public function uploadFiles($gpsDeviceId,$vehicleId,$path, $filename)
    {
        $count = GpsDeviceAttachment::where('vehicle_id','=',$vehicleId)->count();

        if($count < 5){
            $gps_attachment = new GpsDeviceAttachment;
            $gps_attachment->gps_device_id = $gpsDeviceId;
            $gps_attachment->vehicle_id = $vehicleId;
            $gps_attachment->path = $path;
            $gps_attachment->file_name = $filename;
            $gps_attachment->save();
        }
    }

    public function update(Request $request,GpsDevice $gps_device)
    {
        $request->validate([
            'imei' => 'required|unique:gps_devices,imei,' . $gps_device->id,
            'sim_number' => 'required|unique:gps_devices,sim_number,' . $gps_device->id
        ]);

        
        DB::beginTransaction();
        try {
            if($gps_device->update($request->all())){
                $attachments = $request->file('attachments');   
                if(!empty($attachments)){
                    foreach($attachments as $attachment){
                        $filename = $attachment->getClientOriginalName();
                        $path = Storage::disk('public')->put('gps_attachments', $attachment);
                        $uploadedFile = $this->uploadFiles($gps_device->id,$request->vehicle_id, $path, $filename);
                    }    
                }
                
                $vehicle = Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice','gpsdeviceattachments')->where('id', $request->vehicle_id)->first();

                $data=array();
                $data['gps_device_id'] = $gps_device->id;
                $data['device_id'] = $gps_device->device_id;
                $data['name'] = $vehicle->plate_number;
                $data['imei'] = $request->imei;
                $data['sim_number'] = $request->sim_number;
                $data['method'] = 'edit';
        
                $api_assign_id = $this->send_api_assign_gps($data);

                if($api_assign_id){
                    $gps_device->update(['device_id' => $api_assign_id]);
                    DB::commit();
                }else{
                    DB::rollBack();
                }

                return $vehicle;
            }
        } catch (HttpException $ex) {
            DB::rollBack();
            return $vehicle;
        }
    }

    public function send_api_assign_gps($data=array()){
   
        $client = new Client();
        $user_api_hash = Config::get('constants.api.gps_hash');
        $default_headers = Config::get('constants.api.gps_headers');

        $default_params = [
            'name'=> $data['name'],
            'imei'=> $data['imei'],
            'icon_id'=>'0',
            'fuel_measurement_id'=>'1',
            'tail_length'=>'0',
            'min_moving_speed'=>'1',
            'min_fuel_fillings'=>'1',
            'min_fuel_thefts'=>'1',
            'sim_number'=> $data['sim_number'],
            'plate_number'=> $data['name'],
            'device_id'=> isset($data['device_id']) ? $data['device_id'] : '' 
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

    private function destroy_api_gps($device_id){
        $client = new Client();
        $user_api_hash = Config::get('constants.api.gps_hash');

       
        try{
            $response = $client->delete('http://gpstracker.lafilgroup.com/api/destroy_device?user_api_hash='.$user_api_hash.'&device_id='.$device_id);
            return "Success";
        }catch (BadResponseException $ex) {
            $response = $ex->getResponse()->getBody();
            return "Error";
        }
    }

    public function destroy(GpsDevice $gps_device)
    {
        DB::beginTransaction();
        try{
            Vehicle::whereId($gps_device->vehicle_id)->update(['gps_device_id' => null]);
            if($gps_device->delete()){
                $destroy_api = $this->destroy_api_gps($gps_device->device_id);
                if($destroy_api == "Success"){
                    DB::commit();
                }else{
                    DB::rollBack();  
                }
                $vehicle = Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice','gpsdeviceattachments')->where('id', $gps_device->vehicle_id)->first();
                return $vehicle;
            }else{
                DB::rollBack();
                $vehicle = Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice','gpsdeviceattachments')->where('id', $gps_device->vehicle_id)->first();
                return $vehicle;
            }
        }catch (NotFoundHttpException $e) {
            DB::rollBack();
            $response = $e->getResponse();
            return $response;
        }
    }

    public function downloadGPSAttachment($fileId)
    {
        $file = GpsDeviceAttachment::findOrfail($fileId);

        ob_end_clean();
        return response()->download(storage_path("app/public/".$file->path), $file->file_name);
    }
    
    public function deleteGPSAttachment(GpsDeviceAttachment $file)
    {
        DB::beginTransaction();
        $vehicle_id = $file->vehicle_id;
        try{
            if($file->delete()){
                DB::commit();
                $vehicle = Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice','gpsdeviceattachments')->where('id', $vehicle_id)->first();
                return $vehicle;
            }else{
                DB::rollBack();
                $vehicle = Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice','gpsdeviceattachments')->where('id', $vehicle_id)->first();
                return $vehicle;
            }
        }catch (NotFoundHttpException $e) {
            DB::rollBack();
            $response = $e->getResponse();
            return $response;
        }
    }

    public function reassign_gps_device(Request $request,GpsDevice $gps_device){

        $request->validate([
            'reassign_vehicle_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            GpsDevice::where('id', '=', $gps_device->id)->update(['vehicle_id' => $request->reassign_vehicle_id]);
            // GpsDeviceAttachment::where('gps_device_id', '=', $gps_device->id)->update(['vehicle_id' => $request->reassign_vehicle_id]);
            
            Vehicle::whereId($request->vehicle_id)->update(['gps_device_id' => null]);
            Vehicle::whereId($request->reassign_vehicle_id)->update(['gps_device_id' => $gps_device->id]);

            $client = new Client();
            $user_api_hash = Config::get('constants.api.gps_hash');
            $default_headers = Config::get('constants.api.gps_headers');

            $default_params = [
                'name'=> $request->plate_number,
                'plate_number'=> $request->plate_number,
                'icon_id'=>'0',
                'fuel_measurement_id'=>'1',
                'tail_length'=>'0',
                'min_moving_speed'=>'1',
                'min_fuel_fillings'=>'1',
                'min_fuel_thefts'=>'1',
                'device_id'=> $gps_device->device_id
            ];
  
            try {
                $response = $client->post('http://gpstracker.lafilgroup.com/api/edit_device?user_api_hash='.$user_api_hash, [
                    'headers' => $default_headers,
                    'form_params'=>  $default_params    
                ]); 

                $device_data = $response->getBody();
                $device_data = json_decode($device_data);

                DB::commit();
                return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice','gpsdeviceattachments')->whereIn('id', [$request->reassign_vehicle_id,$request->vehicle_id])->get();

            }catch (BadResponseException $ex) {
                $response = $ex->getResponse()->getBody();
                // return json_decode($response, true);
                DB::rollBack();
                return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice','gpsdeviceattachments')->whereIn('id', [$request->reassign_vehicle_id,$request->vehicle_id])->get();
            }
        }
        catch (HttpException $ex) {
            DB::rollBack();
            return Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice','gpsdeviceattachments')->whereIn('id', [$request->reassign_vehicle_id,$request->vehicle_id])->get();
        }
        
        // dd($vehicle);
    }

    public function storeCheckUp(Request $request){

        $request->validate([
            'check_up_date' => 'required',
        ]);

        $get_check_ups = GpsDeviceCheckUp::where('gps_device_id',$request->gps_device_id)->get();
        DB::beginTransaction();
        try {
            if(GpsDeviceCheckUp::create($request->all())){
                DB::commit(); 
            }else{
                DB::rollBack(); 
            }
            return GpsDeviceCheckUp::where('gps_device_id' , $request->gps_device_id)->get();
        }
        catch (HttpException $ex){
            DB::rollBack();
            return $get_check_ups;
        }
    }

    public function checkUpLogs($vehicle_id){
        return GpsDeviceCheckUp::where('vehicle_id' , $vehicle_id)->get();
    }
    public function gpsAttachments($vehicle_id){
        return GpsDeviceAttachment::where('vehicle_id' , $vehicle_id)->get();
    }

    public function deleteGPSCheckup(GpsDeviceCheckUp $check_up){

        $check_up_id = $check_up->vehicle_id;
        DB::beginTransaction();
        try{
            if($check_up->delete()){
                DB::commit();
                $check_up_log = GpsDeviceCheckUp::where('vehicle_id' , $check_up_id)->first();
                return $check_up_log;
            }else{
                DB::rollBack();
                return $check_up;
            }
        }catch (NotFoundHttpException $e) {
            DB::rollBack();
            $response = $e->getResponse();
            return $check_up;
        }
    }

}
