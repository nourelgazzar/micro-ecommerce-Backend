<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'first_name' => array('required', 'string', 'max:40','regex:/(^[a-zA-Z])/u'),
            'last_name' => array('required', 'string', 'max:40','regex:/(^[a-zA-Z])/u'),
            'email' => array('required', 'string', 'unique:users,email', 'email', 'max:40'),
            'password' => array('required', 'string', 'confirmed', 'max:40')
        ]);

        $admin = Admin::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $admin->createToken('resumetoken')->plainTextToken;

        $response = [
            'admin' => $admin,
            'token' => $token
        ];

        return response($response, 201);

    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => array('required', 'string', 'email', 'max:40'),
            'password' => array('required', 'string', 'max:40')
        ]);

        $admin = Admin::where('email', $fields['email'])->first();
        if (!$admin)
        {
            return response(
                [
                    'Response' => 'Please enter the right email!'
                ], 401);
        }
        else if (!Hash::check($fields['password'], $admin->password))
        {
            return response(
                [
                    'Response' => 'Please enter the right password!'
                ], 401);
        }

        $token = $admin->createToken('admintoken')->plainTextToken;

        $response = [
            'admin' => $admin,
            'token' => $token
        ];

        return response($response, 201);

    }


    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'Response' => 'Logged out'
        ];
    }
}