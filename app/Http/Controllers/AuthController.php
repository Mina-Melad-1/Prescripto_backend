<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $val = $request->validated();

        $user = User::create([
            'name' => $val['name'],
            'email' => $val['email'],
            'password' => Hash::make($val['password']),
        ]);
        return response()->json([
            'message' => 'User registered successfully',
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password
        ], 201);
    }
}
