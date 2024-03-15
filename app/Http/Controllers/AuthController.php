<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * User registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            //Validate requested data
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Validation Error', 'message' => $validator->errors()], 422);
            }

            // Create a new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Create and return the access token
            $token = $user->createToken('Token')->accessToken;

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Server Error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * User login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            //Validate requested data
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Validation Error', 'message' => $validator->errors()], 422);
            }

            // Check if user exists
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['error' => 'User Not Found'], 404);
            }

            // Attempt user authentication
            if (!auth('web')->attempt($request->only('email', 'password'))) {
                return response(['message' => 'Invalid Credentials']);
            }

            // Generate and return access token
            $accessToken = auth('web')->user()->createToken('authToken')->accessToken;

            return response(['token' => $accessToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server Error', 'message' => $e->getMessage()], 500);
        }
    }
}
