<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return redirect('/login');
});


Route::get('logout', function(){
    return redirect('/');
});

// determine if truck has a gps
Route::post('/vehicle-gps', 'VehicleController@getVehicleGps');

Auth::routes();

Route::get('/sample', function () {
    dd(Auth::user()->roles[0]->name);
});

Route::get('/home', 'HomeController@index')->name('home');

// Authenticated Routes
Route::group(['middleware' => 'auth'], function(){
    // Vehicle
    Route::get('/vehicle', 'VehicleController@index');
    Route::post('/vehicle', 'VehicleController@store');
    Route::patch('/vehicle/{vehicle}', 'VehicleController@update');
    Route::delete('/vehicle/{vehicle}', 'VehicleController@destroy');
    Route::get('/vehicle/vendors', 'VehicleController@getVendors');
    Route::get('/vehicle-specific/{id}', 'VehicleController@show');


    Route::post('/filter-vehicle', 'VehicleController@filterVehicle');

    // Categories
    Route::get('/categories', 'CategoryController@index');
    Route::post('/category', 'CategoryController@store');
    Route::patch('/category/{category}', 'CategoryController@update');
    Route::delete('/category/{category}', 'CategoryController@destroy');

    // Capacities
    Route::get('/capacities', 'CapacityController@index');
    Route::post('/capacity', 'CapacityController@store');
    Route::patch('/capacity/{capacity}', 'CapacityController@update');
    Route::delete('/capacity/{capacity}', 'CapacityController@destroy');

    // Indicators
    Route::get('/indicators', 'IndicatorController@index');
    Route::post('/indicator', 'IndicatorController@store');
    Route::patch('/indicator/{indicator}', 'IndicatorController@update');
    Route::delete('/indicator/{indicator}', 'IndicatorController@destroy');

    // Goods
    Route::get('/goods', 'GoodController@index');
    Route::post('/good', 'GoodController@store');
    Route::patch('/good/{good}', 'GoodController@update');
    Route::delete('/good/{good}', 'GoodController@destroy');

    // Based Trucks
    Route::get('/based-trucks', 'BasedTruckController@index');
    Route::post('/based-truck', 'BasedTruckController@store');
    Route::patch('/based-truck/{basedTruck}', 'BasedTruckController@update');
    Route::delete('/based-truck/{basedTruck}', 'BasedTruckController@destroy');

    // Contracts
    Route::get('/contracts', 'ContractController@index');
    Route::post('/contract', 'ContractController@store');
    Route::patch('/contract/{contract}', 'ContractController@update');
    Route::delete('/contract/{contract}', 'ContractController@destroy');

    // Documents
    Route::get('/documents', 'DocumentController@index');
    
    // Download files upload
    Route::get('/download-attachment/{fileId}', 'DocumentController@downloadAttachment');

    // Download files upload
    Route::get('/download-gps-attachment/{fileId}', 'GpsDevicesController@downloadGPSAttachment');

    // Download files upload
    Route::delete('/delete-gps-attachment/{file}', 'GpsDevicesController@deleteGPSAttachment');


    // Users
    Route::get('/users', 'UserController@index')->name('users');
    Route::get('/users-all', 'UserController@indexData');
    Route::post('/user', 'UserController@store');
    Route::patch('/user/{user}', 'UserController@update');
    Route::delete('/user/{user}', 'UserController@destroy');
    Route::post('/change-password', 'UserController@changePassword');

    // Truckers
    Route::get('/truckers', 'TruckerController@index');

    // Plants
    Route::get('/plants', 'PlantController@index');

    // GPS Devices
    Route::get('/gps_devices', 'GpsDevicesController@index');
    Route::post('/gps_device', 'GpsDevicesController@store');
    Route::patch('/gps_device/{gps_device}', 'GpsDevicesController@update');
    Route::get('/send_api_assign_gps', 'GpsDevicesController@send_api_assign_gps');
    Route::delete('/gps_device/{gps_device}', 'GpsDevicesController@destroy');
   
    Route::patch('/reassign_gps_device/{gps_device}', 'GpsDevicesController@reassign_gps_device');

    // Roles
    Route::get('/roles', 'RoleController@index');


    // Vehicle GPS checker
    Route::get('/get-vehicle-gps', 'VehicleController@vehicleGPS');
    Route::post('/vehicle-check-assign-gps', 'GpsDevicesController@vehicleCheckAssignGPS');

});


