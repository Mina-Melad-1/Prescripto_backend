<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Doctor extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'profile_image',
        'password',
        'speciality',
        'degree',
        'experience',
        'address',
        'fees',
        'about',
    ];

    protected $hidden = ['password'];
}
