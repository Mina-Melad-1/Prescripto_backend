<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorRequest;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)
            ->where('role', 'Admin')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Unauthorized - Admin credentials only'
            ], 401);
        }

        $token = $user->createToken('admin_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'role'  => $user->role
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function dashboard()
    {
        return response()->json([
            'message' => 'Welcome Admin',
        ], 200);
    }

    public function add_doctor(DoctorRequest $request)
    {

        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')
                ->store('doctors', 'public');
        }

        $doctor = Doctor::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'profile_image' => $imagePath,
            'speciality'    => $request->speciality,
            'degree'        => $request->degree,
            'experience'    => $request->experience,
            'address'       => $request->address,
            'fees'          => $request->fees,
            'about'         => $request->about,
        ]);

        return response()->json([
            'message' => 'Doctor added successfully',
            'doctor' => $doctor,
        ], 201);
    }



    public function edit_doctor(Request $request, $doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'speciality'    => 'nullable|string|max:255',
            'degree'        => 'nullable|string|max:255',
            'experience'    => 'nullable|string|max:255',
            'address'       => 'nullable|string|max:255',
            'fees'          => 'nullable|numeric',
            'about'         => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('profile_image')) {

            if ($doctor->profile_image && Storage::disk('public')->exists($doctor->profile_image)) {
                Storage::disk('public')->delete($doctor->profile_image);
            }

            $imagePath = $request->file('profile_image')
                ->store('doctors', 'public');

            $doctor->profile_image = $imagePath;
        }

        $doctor->update([
            'name'        => $request->name,
            'speciality'  => $request->speciality,
            'degree'      => $request->degree,
            'experience'  => $request->experience,
            'address'     => $request->address,
            'fees'        => $request->fees,
            'about'       => $request->about,
        ]);

        return response()->json([
            'message' => 'Doctor updated successfully',
            'doctor'  => $doctor->makeHidden([
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
