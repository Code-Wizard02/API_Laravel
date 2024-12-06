<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        //$validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        // $accessToken = $user->createToken('authToken')->accessToken;

        $accessToken = $user->createToken('authToken')->plainTextToken;

        return response(['user' => $user, 'access_token' => $accessToken]);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => ['required', 'email', 'exists:users'],
            'password' => ['required', 'min:8'],
        ]);

        $user = User::where('email', $loginData['email'])->first();
        if (! $user || ! Hash::check($loginData['password'], $user->password)) {
            return response(['message' => 'Invalid credentials'], 401);
        }


        $accessToken = $user->createToken('authToken')->plainTextToken;

        return response(['user' => $user, 'access_token' => $accessToken]);
    }

    public function userProfile()
    {
        $userData = Auth::user();
        return response()->json([
            'status' => true,
            'message' => 'User profile',
            'data' => $userData,
            'id' => $userData->id
        ], 200);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Logged out'
        ], 200);
    }

    public function userResource()
    {
        // $userData = Auth::user();
        // return response()->json([
        //     'status' => true,
        //     'message' => 'User profile',
        //     'data' => $userData,
        //     'id' => $userData->id
        // ], 200);

        $userData = new UserResource(User::findOrFail(Auth::id()));
        return response()->json([
            'status' => true,
            'message' => 'User profile using API resource',
            'data' => $userData,
            'id' => Auth::id()
        ], 200);
    }

    public function userResourceCollection()
    {
        $userData = UserResource::collection(User::all());
        return response()->json([
            'status' => true,
            'message' => 'User profile using API resource collection',
            'data' => $userData
        ], 200);
    } 
}
