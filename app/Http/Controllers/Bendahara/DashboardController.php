<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Get total teachers
        $totalGuru = User::where('role', 'guru')->count();
        
        // Get total teaching hours this month
        $totalTeachingHours = Attendance::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('teaching_hours');
        
        // Calculate estimated total salary (hours x 10000)
        $estimatedSalary = $totalTeachingHours * 10000;
        
        // Get top teachers by teaching hours
        $topTeachers = User::where('role', 'guru')
            ->select('users.id', 'users.name', 'users.niy')
            ->leftJoin('attendances', function($join) use ($currentMonth, $currentYear) {
                $join->on('users.id', '=', 'attendances.user_id')
                    ->whereMonth('attendances.date', $currentMonth)
                    ->whereYear('attendances.date', $currentYear);
            })
            ->selectRaw('SUM(COALESCE(attendances.teaching_hours, 0)) as total_hours')
            ->groupBy('users.id', 'users.name', 'users.niy')
            ->orderByDesc('total_hours')
            ->limit(5)
            ->get();
        
        return view('bendahara.dashboard', compact(
            'totalGuru',
            'totalTeachingHours',
            'estimatedSalary',
            'topTeachers',
            'currentMonth',
            'currentYear'
        ));
    }
}
