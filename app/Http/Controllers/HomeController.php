<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        session([
            'header_text' => 'Vehicles',
            'level' => Auth::user()->level(),
            'role-name' => Auth::user()->roles[0]->name
        ]);
        
        
        return view('home');
    }
}
