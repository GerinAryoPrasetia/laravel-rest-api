<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //validation request on body
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email', //uniqe terhadap tabel users dan field email
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return response($response, 201);
    }

    public function login(Request $request)
    {
        //validation request on body
        $fields = $request->validate([
            'email' => 'required|string', //uniqe terhadap tabel users dan field email
            'password' => 'required|string',
        ]);

        //check email
        $user = User::where('email', $fields['email'])->first(); //query

        //check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }
        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return response($response, 201);
    }
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'logged out'
        ];
    }
}
