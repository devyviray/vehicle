<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vehicle;

class ApiController extends Controller
{
   public function plateNumberVendorCode(){
    return Vehicle::with('vendor')->orderBy('id', 'desc')->get();
   }
}
