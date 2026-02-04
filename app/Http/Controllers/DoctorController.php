<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Find doctor by email
        $doctor = Doctor::where('email', $request->email)->first();

        if (!$doctor || !Hash::check($request->password, $doctor->password)) {
            return response()->json([
                'message' => 'Invalid login details ! Doctors Only !'
            ], 401);
        }

        // Create token for this doctor
        $token = $doctor->createToken('doctor_token')->plainTextToken;

        return response()->json([
            'token'  => $token
        ]);
    }

    public function logout(Request $request)
    {
        // Use doctor guard for sanctum
        $doctor = $request->user('doctor');

        if ($doctor) {
            $doctor->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Doctor logged out successfully'
        ]);
    }

    public function profile(Request $request)
    {
        // Get authenticated doctor (via sanctum)
        $doctor = $request->user('doctor');

        if (!$doctor) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'profile_image' => $doctor->profile_image ?? "",
            'name'          => $doctor->name,
            'speciality'    => $doctor->speciality ?? "General Physician",
            'degree'        => $doctor->degree ?? "",
            'experience'    => $doctor->experience,
            'address'       => $doctor->address ?? "",
            'fees'          => $doctor->fees ?? "",
            'about'         => $doctor->about ?? "",
        ]);
    }
}
