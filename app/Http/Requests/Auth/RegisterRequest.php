<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'min:9', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:tenant,landlord'], 
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'birth_date' => ['required', 'date', 'before:today'],
            'personal_image' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'identity_image' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:4096'],
        ];
    }
}