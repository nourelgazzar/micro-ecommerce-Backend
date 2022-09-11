<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:20|regex:/(^([a-zA-Z]+)?$)/u',
            'last_name' => 'required|string|max:20|regex:/(^([a-zA-Z]+)?$)/u',
            'email' => 'required|string|unique:admins,email|email|max:40',
            'password' => 'required|string|confirmed|max:40',
        ]);
        $cart = Cart::create([]);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'balance' => 10000,
            'cart_id' => $cart->id,
        ]);

        $response = [
            'user' => $user,
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:40',
            'password' => 'required|string|max:40',
        ]);

        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response(
                [
                    'Response' => 'Please enter the right email or password!',
                ],
                401
            );
        }

        $token = $user->createToken('admintoken')->plainTextToken;

        $response = [
            'admin' => $user,
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
