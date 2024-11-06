<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    //Register Api
    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            "status" => true,
            'message' => 'User created successfully'
        ]);
    }
    //Login Api
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $token = JWTAuth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if(!empty($token)){
            return response()->json([
                "status" => true,
                'message' => 'User logged in successfully',
                'token' => $token
            ]);
        }
        return response()->json([
            "status" => false,
            'message' => 'Invalid credentials'
        ]);

    }

    //Profile Api
    public function profile(){
        $userData = auth()->user();

        return response()->json([
            "status" => true,
            'message' => 'User profile',
            'user' => $userData
        ]);
    }

    //Refresh Token Api
    public function refreshToken(){
        $newToken = JWTAuth::refresh();

        return response()->json([
            "status" => true,
            'message' => 'User token refreshed successfully',
            'token' => $newToken
        ]);
    }

    //Logout Api
    public function logout(){
        auth()->logout();

        return response()->json([
            "status" => true,
            'message' => 'User logged out successfully'
        ]);
    }

}
