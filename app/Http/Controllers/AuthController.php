<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Doctor;

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
    public function login(Request $request)
    {
        $request->validate([
            "email"    => "required|string|email",
            "password" => "required|string",
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully'
        ], 200);
    }



    public function profile(Request $request)
    {
        $user = $request->user(); 

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated Please log in first'
            ], 401);
        }

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'gender' => $user->gender,
            'birthday' => $user->birthday,
            'role' => $user->role,
            'profile_image' => $user->profile_image
                ? url('storage/' . $user->profile_image)
                : null,
        ]);
    }


    public function edit_profile(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|regex:/^\+?[0-9]{8,15}$/',
            'address'       => 'nullable|string|max:255',
            'gender'        => 'nullable|in:male,female',
            'birthday'      => 'nullable|date',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle Image Upload

        if ($request->hasFile('profile_image')) {

            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $imagePath = $request->file('profile_image')
                ->store('users', 'public');

            $user->profile_image = $imagePath;
        }

        $user->update([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'gender'   => $request->gender,
            'birthday' => $request->birthday,
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->makeHidden([
                'role',
                'email_verified_at',
                'created_at',
                'updated_at',
            ])
        ]);
    }

    public function doctors()
    {
        $doctors = Doctor::latest()->get();

        $doctors->transform(function ($doctor) {
            $doctor->profile_image = $doctor->profile_image
                ? asset('storage/' . $doctor->profile_image)
                : null;

            return $doctor;
        });

        return response()->json([
            'data' => $doctors
        ], 200);
    }
}
