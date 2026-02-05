<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJurnalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isGuru();
    }

    public function rules(): array
    {
        $validClasses = \App\Models\ClassModel::getActiveClassNames();
        
        return [
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'subject' => ['required', 'string', 'max:255'],
            'class' => ['required', 'string', 'in:' . implode(',', $validClasses)],
            'meeting_number' => ['required', 'integer', 'min:1', 'max:100'],
            'tp' => ['required', 'string'],
            'material' => ['required', 'string'],
            'time_slot' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'log_date' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'academic_year_id.required' => 'Tahun ajaran harus dipilih',
            'academic_year_id.exists' => 'Tahun ajaran tidak valid',
            'subject.required' => 'Mata pelajaran harus diisi',
            'class.required' => 'Kelas harus dipilih',
            'class.in' => 'Kelas yang dipilih tidak valid',
            'meeting_number.required' => 'Pertemuan ke berapa harus diisi',
            'meeting_number.integer' => 'Pertemuan harus berupa angka',
            'meeting_number.min' => 'Pertemuan minimal 1',
            'tp.required' => 'Tujuan Pembelajaran harus diisi',
            'material.required' => 'Materi harus diisi',
            'time_slot.required' => 'Jam pelajaran harus diisi',
            'log_date.required' => 'Tanggal harus diisi',
            'log_date.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
        ];
    }
}