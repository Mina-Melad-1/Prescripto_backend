<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:doctors,email',
            'password'      => 'required|min:6',
            'speciality'    => 'required|string',
            'fees'          => 'required|numeric',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Doctor name is required',
            'email.unique'  => 'This email already exists',
            'fees.numeric'  => 'Fees must be a number',
        ];
    }
}
