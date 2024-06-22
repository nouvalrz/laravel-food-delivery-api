<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function buyerRegister(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone_number' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $userParams = $request->only('name', 'email');
            $userParams['password'] = bcrypt($request->password);
            $userParams['roles'] = 'buyer';

            $user = User::create($userParams);
            $buyer = $user->buyer()->create([
                'phone_number' => $request->phone_number,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Buyer registered successfully',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to register buyer',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function merchantRegister(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'image' => 'required|image',
        ]);

        try {
            DB::beginTransaction();

            $userParams = $request->only('name', 'email');
            $userParams['password'] = bcrypt($request->password);
            $userParams['roles'] = 'merchant';

            $user = User::create($userParams);

            
            $merchant = $user->merchant()->create([
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'lat' => $request->lat,
                'long' => $request->long,
                'image' => $request->file('image')->store('images/merchants')
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Merchant registered successfully',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to register merchant',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function driverRegister(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone_number' => 'required|string',
            'license_plate' => 'required|string',
            'image' => 'required|image',
        ]);

        try {
            DB::beginTransaction();

            $userParams = $request->only('name', 'email');
            $userParams['password'] = bcrypt($request->password);
            $userParams['roles'] = 'driver';

            $user = User::create($userParams);
            $driver = $user->driver()->create([
                'phone_number' => $request->phone_number,
                'license_plate' => $request->license_plate,
                'image' => $request->file('image')->store('images/drivers')
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Driver registered successfully',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to register driver',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if(!Auth::attempt($credentials)){
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token', [$user->roles])->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login success',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout success'
        ]);
    }

    
}
