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
        $getBGJobs = BackgroundJobLogs::orderBy('id','desc')->first();

        if (is_null($getBGJobs)) {
            BackgroundJobLogs::create([
                'name' => 'update:driver',
                'start_time' => date('Y-m-d H:i:s'),
            ]);
            
        } elseif (!is_null($getBGJobs->end_time)) {
            BackgroundJobLogs::create([
                'name' => 'update:driver',
                'start_time' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $getBGJobs->end_time = date('Y-m-d H:i:s');
            $getBGJobs->save();
        }

        $getBGJobs = BackgroundJobLogs::orderBy('id','desc')->first();

        $dateFilter = !is_null($getBGJobs->end_time) ? $getBGJobs->end_time : $getBGJobs->start_time;

        echo $dateFilter . ' || ****************************** ';

        $drivers = Driverversions::with('drivers_info')->orderBy('id','desc')->limit(10)->get();

        foreach ($drivers as $driver) {
            echo $driver->plate_number . ' || ';
            $vehicles = Vehicle::where('plate_number', '=', $driver->plate_number);
            $checkVehicle = $vehicles->first();
            if ($checkVehicle) {
                echo 'Validity Start : ' . date('Y-m-d', strtotime($driver->drivers_info->start_validity_date)) . ' || ';
                echo 'Validity End : ' . date('Y-m-d', strtotime($driver->drivers_info->end_validity_date)) . ' || *********************** ';
                $vehicles->update([
                    'validity_start_date' => date('Y-m-d', strtotime($driver->drivers_info->start_validity_date)),
                    'validity_end_date' => date('Y-m-d', strtotime($driver->drivers_info->end_validity_date)),
                ]);
                /* $vehicles->validity_start_date = date('Y-m-d', strtotime($driver->drivers_info->start_validity_date));
                $vehicles->validity_end_date = date('Y-m-d', strtotime($driver->drivers_info->end_validity_date));
                $vehicles->save(); */
            } /* else {
                
            } */
        }

        $getBGJobs->end_time = date('Y-m-d H:i:s');
        $getBGJobs->save();
    }
}
