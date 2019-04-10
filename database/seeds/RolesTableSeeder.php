<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (Role::where('name', '=', 'It')->first() === null) {
            $itRole = Role::create([
                'name'        => 'IT',
                'slug'        => 'it',
                'description' => 'It Role',
                'level'       => 6,
            ]);
        }

        if (Role::where('name', '=', 'President')->first() === null) {
            $vehicleCustodianRole = Role::create([
                'name'        => 'Vehicle Custodian',
                'slug'        => 'vehicle custodian',
                'description' => 'Vehicle custodian Role',
                'level'       => 5,
            ]);
        }

        if (Role::where('name', '=', 'Evp')->first() === null) {
            $alcHeadRole = Role::create([
                'name'        => 'ALC Head',
                'slug'        => 'alc head',
                'description' => 'Alc head Role',
                'level'       => 4,
            ]);
        }

        if (Role::where('name', '=', 'Vp')->first() === null) {
            $alcDomRole = Role::create([
                'name'        => 'ALC DOM',
                'slug'        => 'alc dom',
                'description' => 'Alc dom Role',
                'level'       => 3,
            ]);
        }

        if (Role::where('name', '=', 'Avp')->first() === null) {
            $rtcRole = Role::create([
                'name'        => 'RTC',
                'slug'        => 'rtc',
                'description' => 'Rtc Role',
                'level'       => 2,
            ]);
        }

        if (Role::where('name', '=', 'Coordinator')->first() === null) {
            $apStaffRole = Role::create([
                'name'        => 'AP STAFF',
                'slug'        => 'ap staff',
                'description' => 'Ap staff Role',
                'level'       => 1,
            ]);
        }
    }
}
