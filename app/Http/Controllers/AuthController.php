<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:80',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|max:64',
            'device_name' => 'nullable|string|max:80',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken($data['device_name'] ?? 'mobile')->plainTextToken;

        return response()->json([
            'user' => ['id'=>$user->id,'name'=>$user->name,'email'=>$user->email],
            'token' => $token,
        ], 201);
    }


public function login(Request $r)
{
    $data = $r->validate([
        'email' => 'required|email',
        'password' => 'required|string',
        'device_name' => 'required|string|max:80',
    ]); // returns 422 JSON on failure when Accept: application/json is sent [web:492]

    $email = strtolower(trim($data['email'])); // normalize input [web:277]
    $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

    if (! $user || ! Hash::check($data['password'], $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]); // structured 422 so clients can show field errors [web:497][web:492]
    }

    $token = $user->createToken($data['device_name'])->plainTextToken; // Sanctum mobile token [web:61]

    return response()->json([
        'user' => ['id'=>$user->id,'name'=>$user->name,'email'=>$user->email],
        'token' => $token,
    ]); // 200 JSON with token [web:61]
}


    public function logout(Request $r)
    {
        $r->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function logoutAll(Request $r)
    {
        $r->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out from all devices']);
    }
}
