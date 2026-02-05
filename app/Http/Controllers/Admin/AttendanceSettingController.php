<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;

class AttendanceSettingController extends Controller
{
    public function index()
    {
        $settings = AttendanceSetting::where('is_active', 1)->first();

        return view('admin.attendance.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'work_start' => 'required|date_format:H:i',
            'late_time' => 'required|date_format:H:i',
            'work_end' => 'required|date_format:H:i',
            'grace_period' => 'required|integer|min:0|max:30',
            'working_days_per_month' => 'required|integer|min:1|max:31',
            'allow_early_checkin' => 'boolean',
        ]);

        $settings = AttendanceSetting::where('is_active', 1)->first();

        if (!$settings) {
            $settings = AttendanceSetting::create([
                'is_active' => 1
            ]);
        }

        $settings->update([
            'work_start' => $request->work_start . ':00',
            'late_time' => $request->late_time . ':00',
            'work_end' => $request->work_end . ':00',
            'grace_period' => $request->grace_period,
            'working_days_per_month' => $request->working_days_per_month,
            'allow_early_checkin' => $request->has('allow_early_checkin') ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.attendance.settings')
            ->with('success', 'Pengaturan absensi berhasil diperbarui');
    }
}
