<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'first_name' => ['required', 'string', 'max:40', 'regex:/(^([a-zA-Z]+)(\d+)?$)/u'],
            'last_name' => ['required', 'string', 'max:40', 'regex:/(^([a-zA-Z]+)(\d+)?$)/u'],
            'email' => ['required', 'string', 'unique:users,email', 'email', 'max:40'],
            'password' => ['required', 'string', 'confirmed', 'max:40'],
        ]);

        $admin = Admin::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $admin->createToken('resumetoken')->plainTextToken;

        $response = [
            'admin' => $admin,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required', 'string', 'email', 'max:40'],
            'password' => ['required', 'string', 'max:40'],
        ]);

        $admin = Admin::where('email', $fields['email'])->first();
        if (! $admin || ! Hash::check($fields['password'], $admin->password)) {
            return response(
                [
                    'Response' => 'Please enter the right email or password!',
                ], 401);
        }

        $token = $admin->createToken('admintoken')->plainTextToken;

        $response = [
            'admin' => $admin,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'Response' => 'Logged out',
        ];
    }
}
