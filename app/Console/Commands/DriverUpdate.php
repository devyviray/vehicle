<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Vehicle;
use App\Driver;
use App\DriverTruck;
use App\Driverversions;
use App\Truck;
use App\BackgroundJobLogs;

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

        $drivers = Driverversions::with('drivers_info')
            // ->whereDate('updated_at', '>', $dateFilter)
            ->get();

        foreach ($drivers as $driver) {
            $vehicles = Vehicle::where('plate_number', '=', $driver->plate_number)->whereDate('validity_end_date','>=', date('Y-m-d'));
            $checkVehicle = $vehicles->first();

            if ($driver->drivers_info) {
                $driver_name = $driver->drivers_info->name;
                $final_driver_name = str_replace('.','',$driver_name);
                // $driver_name1 = str_replace('  JR','',$driver_name);
                // $driver_name1 = str_replace(' JR','',$driver_name);
                // $driver_name2 = str_replace(' SR','',$driver_name1);
                // $final_driver_name = str_replace(' III','',$driver_name3);

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

        /* if ($getBGJobs->id == 1) {
            $this->trucksJson();
        } */

        $getBGJobs->end_time = date('Y-m-d H:i:s');
        $getBGJobs->save();
        // $this->trucksJson();
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
            $vehicles = Vehicle::where('plate_number', '=', $driver->trucks_info->plate_number)->where('driver_name',null);
            $checkVehicle = $vehicles->first();

            if ($driver->drivers_info) {
                $driver_name = $driver->drivers_info->name;
                $final_driver_name = str_replace('.','',$driver_name);
                // $driver_name1 = str_replace('  JR','',$driver_name);
                // $driver_name1 = str_replace(' JR','',$driver_name);
                // $driver_name2 = str_replace(' SR','',$driver_name1);
                // $final_driver_name = str_replace(' III','',$driver_name3);

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
}
