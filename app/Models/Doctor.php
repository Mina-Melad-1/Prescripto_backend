<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
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
