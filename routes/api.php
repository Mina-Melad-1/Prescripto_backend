<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
Route::post('/editProfile', [AuthController::class, 'edit_profile'])->middleware('auth:sanctum');

Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout'])->middleware('auth:sanctum', 'admin');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->middleware('auth:sanctum', 'admin');
Route::post('/admin/add-doctor', [AdminController::class, 'add_doctor'])->middleware('auth:sanctum', 'admin');
Route::post('/admin/edit-doctor/{doctorId}', [AdminController::class, 'edit_doctor'])->middleware('auth:sanctum', 'admin');
Route::get('/admin/doctors', [AdminController::class, 'doctors'])->middleware('auth:sanctum', 'admin');



