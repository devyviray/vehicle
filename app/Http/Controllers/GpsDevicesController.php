<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    GpsDevice,
    Vehicle
};

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;



class GpsDevicesController extends Controller
{

    private function get_user_api_hash(){
        return '$2y$10$cgtB39dh3RjWI411T6MwjuYUHrRTv/4iUC.A7RSTktZQrjJ5UWl1W';
    }

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

        $gps_device = GpsDevice::create($request->all());
        Vehicle::whereId($request->vehicle_id)->update(['gps_device_id' => $gps_device->id]);

        $vehicle = Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice')->where('id', $request->vehicle_id)->first();

        $data=array();
        $data['gps_device_id'] = $gps_device->id;
        $data['name'] = $vehicle->plate_number;
        $data['imei'] = $request->imei;
        $data['sim_number'] = $request->sim_number;
        $data['method'] = 'add';

        try {
            $api_assign = $this->send_api_assign_gps($data);
            return $vehicle;
        } catch (HttpException $ex) {
            return $vehicle;
        }

    }

    public function update(Request $request,GpsDevice $gps_device)
    {
        $request->validate([
            'imei' => 'required|unique:gps_devices,imei,' . $gps_device->id,
            'sim_number' => 'required|unique:gps_devices,sim_number,' . $gps_device->id
        ]);

        $gps_device->update($request->all());

        $vehicle = Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice')->where('id', $request->vehicle_id)->first();

        $data=array();
        $data['gps_device_id'] = $gps_device->id;
        $data['device_id'] = $gps_device->device_id;
        $data['name'] = $vehicle->plate_number;
        $data['imei'] = $request->imei;
        $data['sim_number'] = $request->sim_number;
        $data['method'] = 'edit';

        try {
            $api_assign = $this->send_api_assign_gps($data);
            return $vehicle;
        } catch (HttpException $ex) {
            return $vehicle;
        }
    }

    public function send_api_assign_gps($data=array()){
   
        $client = new Client();
        $user_api_hash = $this->get_user_api_hash();

        $default_headers = [
            'cache-control' => 'no-cache',
            'Connection' => 'keep-alive',
            'Content-Length' => '961',
            'Accept-Encoding' => 'gzip, deflate',
            'Host' => 'gpstracker.lafilgroup.com',
            'Cache-Control' => 'no-cache',
            'Accept' => '*/*',
            'content-type' => 'application/x-www-form-urlencoded',
        ];

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

            GpsDevice::whereId($data['gps_device_id'])->update(['device_id' => $device_data->id]);

           return 'success';

        }catch (BadResponseException $ex) {
            $response = $ex->getResponse()->getBody();
            return json_decode($response, true);
        }
    
    }

    private function destroy_api_gps($device_id){
        $client = new Client();
        $user_api_hash = $this->get_user_api_hash();
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
        try{
            $destroy_api = $this->destroy_api_gps($gps_device->device_id);
            if($destroy_api == "Success"){
                Vehicle::whereId($gps_device->vehicle_id)->update(['gps_device_id' => null]);
                if($gps_device->delete()){
                    $vehicle = Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice')->where('id', $gps_device->vehicle_id)->first();
                    return $vehicle;
                }
            }else{
                $vehicle = Vehicle::with('category','capacity', 'indicator', 'good', 'basedTruck', 'contract', 'documents', 'user', 'vendor', 'subconVendor', 'plants','gpsdevice')->where('id', $gps_device->vehicle_id)->first();
                return $vehicle;
            }
        }catch (NotFoundHttpException $e) {
            $response = $e->getResponse();
            return $response;
        }
        
    }

}
