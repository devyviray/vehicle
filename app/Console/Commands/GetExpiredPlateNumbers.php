<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\{
    Vehicle,
    PlantVehicle,
    PlantVehicleDeleted,
    BackgroundJobLogs
};

class GetExpiredPlateNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired:plate-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expired plate numbers successfully saved';

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
        $start_time = microtime(true);
        $expiration_date = Carbon::now()->subDays(1);

        $vehicles = Vehicle::whereDate('validity_end_date',$expiration_date)
            ->get();
        
        $plant_vehicles = PlantVehicle::whereIn('vehicle_id',$vehicles->pluck('id'))
            ->get();

        foreach($plant_vehicles as $plant_vehicle){
            PlantVehicleDeleted::create(['plant_vehicle_id' => $plant_vehicle['id'], 
                    'plant_id' => $plant_vehicle['plant_id'], 
                    'vehicle_id'=> $plant_vehicle['vehicle_id'],
                    'user_id' => 43
                ]);
        }

        /** 
        ** Update updated_at field of plate numbers with the same value of expired plate numbers
        *  for SAP to capture on the next run
        */
        Vehicle::whereIn('plate_number',$vehicles->pluck('plate_number'))
            ->whereDate('validity_end_date','>',$expiration_date)
            ->update(['updated_at' => Carbon::now()]);


        $time_elapsed_in_secs = microtime(true) - $start_time;
        $time_elapsed_in_mins = $time_elapsed_in_secs / 60;
        // Store logs
        BackgroundJobLogs::create([
                'name' => 'expired:plate-numbers',
                'start_time' => $start_time,
                'end_time' => microtime(true)
            ]);
    }
}
