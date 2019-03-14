<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    Vehicle
};

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Vehicle::orderBy('id', 'desc')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'indicator_id' => 'required',
            'good_id' => 'required',
            'allowed_total_weight' => 'required',
            'remarks' => 'required',
            'based_truck_id' => 'required',
            'contract_id' => 'required',
            'document_id' => 'required',
            'user_id' => 'required',
            // 'validity_start_date' => 'required',
            // 'validity_end_date' => 'required',
            // 'date' => 'required',
            // 'time' => 'required'
        ]);
        return Vehicle::create($request->all());
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
            'indicator_id' => 'required',
            'good_id' => 'required',
            'allowed_total_weight' => 'required',
            'remarks' => 'required',
            'based_truck_id' => 'required',
            'contract_id' => 'required',
            'document_id' => 'required',
            'user_id' => 'required',
            // 'validity_start_date' => 'required',
            // 'validity_end_date' => 'required',
            // 'date' => 'required',
            // 'time' => 'required'
        ]);

        if($vehicle->update($request->all())){
            return $vehicle;
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
