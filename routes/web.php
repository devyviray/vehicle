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

Auth::routes();

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

    // Users
    Route::get('/users', 'UserController@index')->name('users');
    Route::get('/users-all', 'UserController@indexData');
    Route::post('/user', 'UserController@store');
    Route::patch('/user/{user}', 'UserController@update');
    Route::delete('/user/{user}', 'UserController@destroy');

    // Truckers
    Route::get('/truckers', 'TruckerController@index');

    // Plants
    Route::get('/plants', 'PlantController@index');

    // Roles
    Route::get('/roles', 'RoleController@index');

});


