<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:20|regex:/(^([a-zA-Z]+)?$)/u',
            'last_name' => 'required|string|max:20|regex:/(^([a-zA-Z]+)?$)/u',
            'email' => 'required|string|unique:admins,email|email|max:40',
            'password' => 'required|string|confirmed|max:40',
        ]);
        $admin = Admin::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
<<<<<<< HEAD
=======
        
>>>>>>> efc6b7f44b5ba89c2fe4b446466592dbd8fd7e61$admin->assignRole('admin');
        $response = [
            'admin' => $admin,
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:40',
            'password' => 'required|string|max:40',
        ]);

        $admin = Admin::where('email', $request->email)->first();
        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            return response(
                [
                    'Response' => 'Please enter the right email or password!',
                ],
                401
            );
        }

        $token = $admin->createToken('admintoken')->plainTextToken;

        $response = [
            'admin' => $admin,
            'token' => $token,
        ];

        return response($response, 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'Response' => 'Logged out',
        ];
    }
}
