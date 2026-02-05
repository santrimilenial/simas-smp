<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // bisa ganti cek role admin
    }

    public function rules(): array
    {
        return [
            'check_in_time' => 'required|date_format:H:i',
            'late_time' => 'required|date_format:H:i|after:check_in_time',
            'check_out_time' => 'nullable|date_format:H:i|after:late_time',
            'grace_period' => 'required|integer|min:0|max:30',
            'working_days' => 'required|array|min:1',
            'working_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'allow_early_checkin' => 'boolean',
            'require_late_notes' => 'boolean',
            'auto_checkout' => 'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'allow_early_checkin' => $this->boolean('allow_early_checkin'),
            'require_late_notes' => $this->boolean('require_late_notes'),
            'auto_checkout' => $this->boolean('auto_checkout'),
        ]);
    }
}
