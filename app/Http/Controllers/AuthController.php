<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //Register
    public function register(Request $request){
        // Validate fields
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:6|string|confirmed'
        ]);

        // Create user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        // return user 5& Token in response
        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ], 200);
    }


    //login
    public function login(Request $request){
        // Validate fields
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        //attemp Login
        if(!Auth::attempt($data))
        {
            return response([
                'message' => 'Invalid Credentials'
            ], 403);
        }

        // return user 5& Token in response
        return response([
            'user' =>  auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ], 200);
    }


    // Logout
    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout Success'
        ], 200);
    }


    // get User details
    public function user(){
        return response([
            'user' => auth()->user()
        ], 200);
    }


    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string'
        ]);

        $image = $this->saveImages($request->image, 'profiles');

        auth()->user()->update([
            'name' => $data['name'],
            'image' => $image
        ]);

        return response([
            'message' => 'User Update',
            'user' => auth()->user()
        ], 200);
    }
}
