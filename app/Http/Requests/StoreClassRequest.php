<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', 'unique:classes,name'],
            'grade_level' => ['required', 'string', 'max:10'],
            'class_group' => ['nullable', 'string', 'max:20'],
            'student_count' => ['required', 'integer', 'min:0', 'max:100'],
            'is_active' => ['boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kelas harus diisi',
            'name.unique' => 'Nama kelas sudah terdaftar',
            'grade_level.required' => 'Tingkat kelas harus diisi',
            'student_count.required' => 'Jumlah siswa harus diisi',
            'student_count.integer' => 'Jumlah siswa harus berupa angka',
            'student_count.min' => 'Jumlah siswa minimal 0',
            'student_count.max' => 'Jumlah siswa maksimal 100',
        ];
    }
}