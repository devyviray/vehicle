<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Vehicle;
use App\Driver;
use App\DriverTruck;
use App\Driverversions;
use App\Truck;
use App\BackgroundJobLogs;
use Illuminate\Support\Facades\DB;

class DriverUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:driver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update drivers info from pickup portal drivers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->driversJson();
    }

    public function driversJson() {
        $this->getDeactivatedDrivers();

        DB::beginTransaction();

        $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

        if (is_null($getBGJobs)) {
            BackgroundJobLogs::create([
                'name' => 'update:driver',
                'start_time' => date('Y-m-d H:i:s'),
            ]);
            $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

            $dateFilter = !is_null($getBGJobs->end_time) ? $getBGJobs->end_time : $getBGJobs->start_time;
            
        } elseif (!is_null($getBGJobs->end_time)) {
            $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

            $dateFilter = !is_null($getBGJobs->end_time) ? $getBGJobs->end_time : $getBGJobs->start_time;

            BackgroundJobLogs::where('name','update:driver')->create([
                'name' => 'update:driver',
                'start_time' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

            $dateFilter = !is_null($getBGJobs->end_time) ? $getBGJobs->end_time : $getBGJobs->start_time;
        }
        
        $data =  Driver::with('hasTrucks.trucks_info')
        ->has('hasTrucks')
        // ->where('id',2161)
        ->where('availability',1)
        ->orderBy('updated_at','ASC')
        ->get();
        
        foreach ($data as $driver) {
            if ($driver->hasTrucks->trucks_info) {
                
                $vehicles = Vehicle::where('plate_number', $driver->hasTrucks->trucks_info->plate_number)->whereDate('validity_end_date','>=', date('Y-m-d'));
                $checkVehicle = $vehicles->first();

                $driver_name = $driver->name;
                $final_driver_name = str_replace('.','',$driver_name);

                $explode_driver = explode(' ', $final_driver_name);
                if (count($explode_driver) == 2) {
                    $firstname = substr($explode_driver[0], 0, 1);
                    $lastname = $explode_driver[1];
                } else {
                    $firstname = substr($explode_driver[0], 0, 1);
                    $suffix = '';
                    $previousData = '';
                    $multipleLastName = '';
                    
                    for ($i=0; $i < count($explode_driver); $i++) {
                        if ($explode_driver[$i] == '' || $explode_driver[$i] == ' ') { // For checking if Suffix is the first word of the name
                            if (str_contains($explode_driver[$i], 'JR.') || str_contains($explode_driver[$i], 'SR.') || str_contains($explode_driver[$i], 'JR') || str_contains($explode_driver[$i], 'SR') || str_contains($explode_driver[$i], 'III') || str_contains($explode_driver[$i], 'IV') || str_contains($explode_driver[$i], 'V')) {
                                $suffix = ' ' . $explode_driver[$i];
                            }
                            break;
                        } else {
                            if ( !str_contains($explode_driver[$i], 'V.') || str_contains($explode_driver[$i], 'JR.') || str_contains($explode_driver[$i], 'SR.') || str_contains($explode_driver[$i], 'JR') || str_contains($explode_driver[$i], 'SR') || str_contains($explode_driver[$i], 'III') || str_contains($explode_driver[$i], 'IV') || str_contains($explode_driver[$i], 'V')) { // Fetch if name has a suffix
                                $suffix = ' ' . $explode_driver[$i]; 
                            } elseif ($previousData != $explode_driver[$i]) { // If previous array data is not equal to current array index
                                $previousData = $explode_driver[$i];
                            }

                            if ($explode_driver[$i] == 'STA' || $explode_driver[$i] == 'DE' || $explode_driver[$i] == 'DEL' || $explode_driver[$i] == 'DELOS' || $explode_driver[$i] == 'DE LOS' || $explode_driver[$i] == 'DELA' || $explode_driver[$i] == 'DELAS' || $explode_driver[$i] == 'DE LAS' || $explode_driver[$i] == 'DELO') {
                                $multipleLastName = $explode_driver[$i] . ' ';
                            }

                            $lastname = $multipleLastName . $previousData . $suffix;
                        }
                    }
                }
                
                $name = $firstname . '. ' . $lastname;

                if ($checkVehicle) {
                    if ($checkVehicle->driver_name !== $name) {
                        $checkVehicle->driver_name = $name;
                        $checkVehicle->driver_validity_start_date = date('Y-m-d', strtotime($driver->start_validity_date));
                        $checkVehicle->driver_validity_end_date = date('Y-m-d', strtotime($driver->end_validity_date));
                        $checkVehicle->save();
                    }
                }
            }
        }

        $getBGJobs->end_time = date('Y-m-d H:i:s');
        $getBGJobs->save();

        DB::commit();
    } 

    public function driversJson3() {
        $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

        if (is_null($getBGJobs)) {
            BackgroundJobLogs::create([
                'name' => 'update:driver',
                'start_time' => date('Y-m-d H:i:s'),
            ]);
            $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

            $dateFilter = !is_null($getBGJobs->end_time) ? $getBGJobs->end_time : $getBGJobs->start_time;
            
        } elseif (!is_null($getBGJobs->end_time)) {
            $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

            $dateFilter = !is_null($getBGJobs->end_time) ? $getBGJobs->end_time : $getBGJobs->start_time;

            BackgroundJobLogs::where('name','update:driver')->create([
                'name' => 'update:driver',
                'start_time' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

            $dateFilter = !is_null($getBGJobs->end_time) ? $getBGJobs->end_time : $getBGJobs->start_time;
        }

        $data =  Driver::with('hasTrucks','hasTrucks.trucks_info')
        ->has('hasTrucks')
        ->where('availability',1)
        ->orderBy('id','DESC')
        ->get();

        foreach ($data as $driver) {
            if ($driver->hasTrucks->trucks_info) {
                // $getLatestDriver = Driverversions::with('drivers_info')->where('plate_number', $driver->hasTrucks->trucks_info->plate_number)->orderBy('updated_at','desc')->first();
                $vehicles = Vehicle::where('plate_number', $driver->hasTrucks->trucks_info->plate_number)->whereDate('validity_end_date','>=', date('Y-m-d'));
                $checkVehicle = $vehicles->first();

                // if (isset($getLatestDriver->drivers_info)) {
                    $driver_name = $driver->name;
                    $final_driver_name = str_replace('.','',$driver_name);

                    $explode_driver = explode(' ', $final_driver_name);
                    if (count($explode_driver) == 2) {
                        $firstname = substr($explode_driver[0], 0, 1);
                        $lastname = $explode_driver[1];
                    } else {
                        $firstname = substr($explode_driver[0], 0, 1);
                        $suffix = '';
                        $previousData = '';
                        $multipleLastName = '';
                        
                        for ($i=0; $i < count($explode_driver); $i++) {
                            if ($explode_driver[$i] == '' || $explode_driver[$i] == ' ') {
                                if (str_contains($explode_driver[$i], 'JR') || str_contains($explode_driver[$i], 'SR') || str_contains($explode_driver[$i], 'III')) {
                                    $suffix = ' ' . $explode_driver[$i];
                                }
                                break;
                            } else {
                                if (str_contains($explode_driver[$i], 'JR') || str_contains($explode_driver[$i], 'SR') || str_contains($explode_driver[$i], 'III')) {
                                    $suffix = ' ' . $explode_driver[$i];
                                } elseif ($previousData != $explode_driver[$i]) {
                                    $previousData = $explode_driver[$i];
                                }

                                if ($explode_driver[$i] == 'STA' || $explode_driver[$i] == 'DE' || $explode_driver[$i] == 'DEL' || $explode_driver[$i] == 'DELOS' || $explode_driver[$i] == 'DE LOS' || $explode_driver[$i] == 'DELA' || $explode_driver[$i] == 'DELAS' || $explode_driver[$i] == 'DE LAS') {
                                    $multipleLastName = $explode_driver[$i] . ' ';
                                    
                                }

                                $lastname = $multipleLastName . $previousData . $suffix;
                            }
                            
                        }
                    }

                    $name = $firstname . '. ' . $lastname;

                    if ($checkVehicle) {
                        if ($checkVehicle->driver_name !== $name) {
                            $checkVehicle->driver_name = $name;
                            $checkVehicle->driver_validity_start_date = date('Y-m-d', strtotime($driver->start_validity_date));
                            $checkVehicle->driver_validity_end_date = date('Y-m-d', strtotime($driver->end_validity_date));
                            $checkVehicle->save();
                        }
                    }
                // }
            }
        }

        $getBGJobs->end_time = date('Y-m-d H:i:s');
        $getBGJobs->save();

        // $this->getDeactivatedDrivers();
    } 

    public function getDeactivatedDrivers() {
        $data =  Driver::with('hasTrucks.trucks_info')
        ->has('hasTrucks')
        ->where('availability',1)
        ->orderBy('updated_at','ASC')
        ->get();
        $platenum = [];
        foreach ($data as $d) {
            $platenum[] = $d->hasTrucks->trucks_info ? $d->hasTrucks->trucks_info->plate_number : '';
        }

        $trucks = Vehicle::whereNotIn('plate_number',$platenum)->whereDate('validity_end_date','>=', date('Y-m-d'))->get();

        foreach ($trucks as $truck) {
            $tr = Vehicle::where('plate_number',$truck->plate_number)->first();

            if (isset($tr->driver_name) && ($tr->driver_name == '' || $tr->driver_name == null || empty($tr->driver_name))) {
            } else {
                $tr->driver_name = '';
                $tr->driver_validity_start_date = '';
                $tr->driver_validity_end_date = '';
                $tr->save();
            }
                
        }
        
    }

    public function trucksJson() {
        $data = DriverTruck::with('drivers_info','trucks_info')
        ->whereHas('drivers_info',function($q){
            $q->where('availability',1);
        })
        ->whereHas('trucks_info',function($q){
            $q->where('availability',1);
        })->get();

        foreach ($data as $driver) {
            $vehicles = Vehicle::where('plate_number', '=', $driver->trucks_info->plate_number)->whereDate('validity_end_date','>=', date('Y-m-d'))->where('driver_name',null);
            $checkVehicle = $vehicles->first();

            if ($driver->drivers_info) {
                $driver_name = $driver->drivers_info->name;
                $final_driver_name = str_replace('.','',$driver_name);

                $explode_driver = explode(' ', $final_driver_name);
                // $firstNameFullText = '';
                if (count($explode_driver) == 2) {
                    $firstname = substr($explode_driver[0], 0, 1);
                    $lastname = $explode_driver[1];
                } else {
                    $firstname = substr($explode_driver[0], 0, 1);
                    $suffix = '';
                    $previousData = '';
                    $multipleLastName = '';
                    
                    for ($i=0; $i < count($explode_driver); $i++) {
                        if ($explode_driver[$i] == '' || $explode_driver[$i] == ' ') {
                            if (str_contains($explode_driver[$i], 'JR') || str_contains($explode_driver[$i], 'SR') || str_contains($explode_driver[$i], 'III')) {
                                $suffix = ' ' . $explode_driver[$i];
                            }
                            break;
                        } else {
                            if (str_contains($explode_driver[$i], 'JR') || str_contains($explode_driver[$i], 'SR') || str_contains($explode_driver[$i], 'III')) {
                                $suffix = ' ' . $explode_driver[$i];
                            } elseif ($previousData != $explode_driver[$i]) {
                                $previousData = $explode_driver[$i];
                            }

                            if ($explode_driver[$i] == 'DE' || $explode_driver[$i] == 'DEL' || $explode_driver[$i] == 'DELOS' || $explode_driver[$i] == 'DE LOS' || $explode_driver[$i] == 'DELA' || $explode_driver[$i] == 'DELAS' || $explode_driver[$i] == 'DE LAS') {
                                $multipleLastName = $explode_driver[$i] . ' ';
                            }

                            $lastname = $multipleLastName . $previousData . $suffix;
                        }
                        
                    }
                }

                // str_replace('.','',$driver_name);

                $name = $firstname . '. ' . $lastname;

                if ($checkVehicle) {
                    $vehicles->update([
                        'driver_name' => $name,
                        'driver_validity_start_date' => date('Y-m-d', strtotime($driver->drivers_info->start_validity_date)),
                        'driver_validity_end_date' => date('Y-m-d', strtotime($driver->drivers_info->end_validity_date)),
                    ]);
                }
            }
        }
    }

    public function new2024(){
        $this->getDeactivatedDrivers();

        DB::beginTransaction();

        $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

        if (is_null($getBGJobs)) {
            BackgroundJobLogs::create([
                'name' => 'update:driver',
                'start_time' => date('Y-m-d H:i:s'),
            ]);
            $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

            $dateFilter = !is_null($getBGJobs->end_time) ? $getBGJobs->end_time : $getBGJobs->start_time;
            
        } elseif (!is_null($getBGJobs->end_time)) {
            $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

            $dateFilter = !is_null($getBGJobs->end_time) ? $getBGJobs->end_time : $getBGJobs->start_time;

            BackgroundJobLogs::where('name','update:driver')->create([
                'name' => 'update:driver',
                'start_time' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

            $dateFilter = !is_null($getBGJobs->end_time) ? $getBGJobs->end_time : $getBGJobs->start_time;
        }
        
        $data =  Driver::with('hasTrucks.trucks_info')
        ->has('hasTrucks')
        ->where('availability',1)
        ->orderBy('updated_at','ASC')
        ->get();
        
        foreach ($data as $driver) {
            if ($driver->hasTrucks->trucks_info) {
                
                $vehicles = Vehicle::where('plate_number', $driver->hasTrucks->trucks_info->plate_number)->whereDate('validity_end_date','>=', date('Y-m-d'));
                $checkVehicle = $vehicles->first();

                if ($driver->first_name && $driver->last_name) {
                    $firstname = substr($driver->first_name, 0, 1);
                    $lastname = $driver->last_name;

                    $name = $firstname . '. ' . $lastname;
                } else {
                    $driver_name = $driver->name;
                    $final_driver_name = str_replace('.','',$driver_name);

                    $explode_driver = explode(' ', $final_driver_name);
                    if (count($explode_driver) == 2) {
                        $firstname = substr($explode_driver[0], 0, 1);
                        $lastname = $explode_driver[1];
                    } else {
                        $firstname = substr($explode_driver[0], 0, 1);
                        $suffix = '';
                        $previousData = '';
                        $multipleLastName = '';
                        
                        for ($i=0; $i < count($explode_driver); $i++) {
                            if ($explode_driver[$i] == '' || $explode_driver[$i] == ' ') { // For checking if Suffix is the first word of the name
                                if (str_contains($explode_driver[$i], 'JR.') || str_contains($explode_driver[$i], 'SR.') || str_contains($explode_driver[$i], 'JR') || str_contains($explode_driver[$i], 'SR') || str_contains($explode_driver[$i], 'III') || str_contains($explode_driver[$i], 'IV') || str_contains($explode_driver[$i], 'V')) {
                                    $suffix = ' ' . $explode_driver[$i];
                                }
                                break;
                            } else {
                                if (str_contains($explode_driver[$i], 'JR.') || str_contains($explode_driver[$i], 'SR.') || str_contains($explode_driver[$i], 'JR') || str_contains($explode_driver[$i], 'SR') || str_contains($explode_driver[$i], 'III') || str_contains($explode_driver[$i], 'IV') || str_contains($explode_driver[$i], 'V')) { // Fetch if name has a suffix
                                    $suffix = ' ' . $explode_driver[$i]; 
                                } elseif ($previousData != $explode_driver[$i]) { // If previous array data is not equal to current array index
                                    $previousData = $explode_driver[$i];
                                }

                                if ($explode_driver[$i] == 'STA' || $explode_driver[$i] == 'DE' || $explode_driver[$i] == 'DEL' || $explode_driver[$i] == 'DELOS' || $explode_driver[$i] == 'DE LOS' || $explode_driver[$i] == 'DELA' || $explode_driver[$i] == 'DELAS' || $explode_driver[$i] == 'DE LAS' || $explode_driver[$i] == 'DELO') {
                                    $multipleLastName = $explode_driver[$i] . ' ';
                                }

                                $lastname = $multipleLastName . $previousData . $suffix;
                            }
                        }
                    }

                    $name = $firstname . '. ' . $lastname;
                }

                if ($checkVehicle) {
                    if ($checkVehicle->driver_name !== $name) {
                        $checkVehicle->driver_name = $name;
                        $checkVehicle->driver_validity_start_date = date('Y-m-d', strtotime($driver->start_validity_date));
                        $checkVehicle->driver_validity_end_date = date('Y-m-d', strtotime($driver->end_validity_date));
                        $checkVehicle->save();
                    }
                }
            }
        }

        $getBGJobs->end_time = date('Y-m-d H:i:s');
        $getBGJobs->save();

        DB::commit();
    }
}