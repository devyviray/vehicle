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
            ->whereDate('updated_at', '>', $dateFilter)
            // ->orderBy('id','desc')->limit(10)
            ->get();

        foreach ($drivers as $driver) {
            // echo $driver->plate_number . ' || ';
            $vehicles = Vehicle::where('plate_number', '=', $driver->plate_number);
            $checkVehicle = $vehicles->first();
            $driver_name = $driver->drivers_info->name;
            $driver_name1 = str_replace(' JR','',$driver_name);
            $driver_name2 = str_replace(' SR','',$driver_name1);
            $driver_name3 = str_replace('.','',$driver_name2);
            $final_driver_name = str_replace(' III','',$driver_name3);

            $explode_driver = explode(' ', $final_driver_name);

            if (count($explode_driver) == 2) {
                $firstname = substr($explode_driver[0], 0, 1);
                $lastname = $explode_driver[1];
            } else {
                $firstname = substr($explode_driver[0], 0, 1);
                for ($i=0; $i < count($explode_driver); $i++) {
                    if (is_null($explode_driver[$i])) {
                        break;
                    }

                    $lastname = $explode_driver[$i];
                }
            }

            $name = $firstname . '. ' . $lastname;

            if ($checkVehicle) {
                $vehicles->update([
                    'driver_name' => $name,
                    'driver_validity_start_date' => date('Y-m-d', strtotime($driver->drivers_info->start_validity_date)),
                    'driver_validity_end_date' => date('Y-m-d', strtotime($driver->drivers_info->end_validity_date)),
                ]);
            }
        }

        $getBGJobs->end_time = date('Y-m-d H:i:s');
        $getBGJobs->save();
    }
}
