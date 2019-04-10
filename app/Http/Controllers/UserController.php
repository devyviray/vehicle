<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    User
};
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['header_text' => 'Users']);

        return view('user.index');
    }

    /**
     * Fetch all users
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        return User::with('roles','basedTrucks')->orderBy('id', 'desc')->get();
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
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'role' => 'required',
            'based_trucks' => 'required_if:role,4|required_if:role,5|required_if:role,6'

        ]);

        if($user = User::create($request->all())){
            // Assigning of role
            $user->syncRoles($request->role);
            // Assigning of based trucks
            $user->basedTrucks()->sync( (array) $request->based_trucks);
            return $user;   
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' .$user->id,
            'role' => 'required',
            'based_trucks' => 'required_if:role,4|required_if:role,5|required_if:role,6'

        ]);

        if($user->update($request->all())){
            // Assigning of role
            $user->syncRoles($request->role);
            // Assigning of based trucks
            $user->basedTrucks()->sync( (array) $request->based_trucks);

            return User::with('roles', 'basedTrucks')->where('id', $user->id)->first();   
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->delete()){
            return $user;
        }
    }

     /*
     * Change password 
     * 
     * @return \Illuminate\Http\Response
     */

    public function changePassword(Request $request){

        $validator = $request->validate([
            'user_id' => 'required',
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ]);
    
        $user = User::findOrFail($request->user_id);
        $user->password = bcrypt($request->input('new_password'));
        $user->save();

        return $user;
    }
}
