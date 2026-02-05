<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UpdateGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->guru)],
            'niy' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($this->guru)],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama guru harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah terdaftar',
            'niy.required' => 'NIY harus diisi',
            'niy.unique' => 'NIY sudah terdaftar',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ];
    }
}