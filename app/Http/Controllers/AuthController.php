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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        // Create user
        $user = User::created([
            'name' => $data['name'],
            'emai' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

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
        if(!Auth::attemp($data))
        {
            return response([
                'message' => 'Invalid Credentials'
            ], 403);
        }

        // return user 5& Token in response
        return response([
            'user' =>  auth()->user,
            'token' => auth()->user->createToken('secret')->plainTextToken
        ], 200);
    }


    // Logout
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response([
           'message' => 'Logout Success'
        ], 200);
    }
}
