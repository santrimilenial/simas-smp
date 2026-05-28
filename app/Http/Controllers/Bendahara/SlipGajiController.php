<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\SlipGaji;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SlipGajiController extends Controller
{
    /**
     * Display a listing of salary slips
     */
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $status = $request->get('status');

        $query = SlipGaji::with(['user', 'creator'])
            ->forMonth($month, $year)
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $slipGaji = $query->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total' => SlipGaji::forMonth($month, $year)->count(),
            'draft' => SlipGaji::forMonth($month, $year)->draft()->count(),
            'approved' => SlipGaji::forMonth($month, $year)->approved()->count(),
            'paid' => SlipGaji::forMonth($month, $year)->paid()->count(),
            'total_amount' => SlipGaji::forMonth($month, $year)->sum('total_amount'),
        ];

        return view('bendahara.slip-gaji.index', compact('slipGaji', 'month', 'year', 'stats'));
    }

    /**
     * Show form for generating salary slips
     */
    public function create(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Get teachers with their teaching hours for the month
        $teachers = User::where('role', 'guru')
            ->select('users.id', 'users.name', 'users.niy')
            ->leftJoin('attendances', function($join) use ($month, $year) {
                $join->on('users.id', '=', 'attendances.user_id')
                    ->whereYear('attendances.date', $year)
                    ->whereMonth('attendances.date', $month);
            })
            ->selectRaw('SUM(COALESCE(attendances.teaching_hours, 0)) as total_teaching_hours')
            ->groupBy('users.id', 'users.name', 'users.niy')
            ->orderBy('users.name')
            ->get();

        // Check which teachers already have slip gaji for this period
        $existingSlips = SlipGaji::forMonth($month, $year)->pluck('user_id')->toArray();

        return view('bendahara.slip-gaji.create', compact('teachers', 'month', 'year', 'existingSlips'));
    }

    /**
     * Store new salary slips
     */
    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'teacher_ids' => 'required|array|min:1',
            'teacher_ids.*' => 'exists:users,id',
            'rate_per_hour' => 'required|numeric|min:0',
        ]);

        $month = $request->month;
        $year = $request->year;
        $ratePerHour = $request->rate_per_hour;
        $teacherIds = $request->teacher_ids;

        $created = 0;
        $skipped = 0;

        foreach ($teacherIds as $teacherId) {
            // Check if slip already exists
            $exists = SlipGaji::where('user_id', $teacherId)
                ->where('month', $month)
                ->where('year', $year)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            // Get total teaching hours for this teacher
            $totalHours = Attendance::where('user_id', $teacherId)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('teaching_hours');

            $totalAmount = $totalHours * $ratePerHour;

            SlipGaji::create([
                'user_id' => $teacherId,
                'created_by' => auth()->id(),
                'month' => $month,
                'year' => $year,
                'total_teaching_hours' => $totalHours,
                'rate_per_hour' => $ratePerHour,
                'total_amount' => $totalAmount,
                'status' => 'draft',
            ]);

            $created++;
        }

        $message = "Berhasil membuat {$created} slip gaji.";
        if ($skipped > 0) {
            $message .= " {$skipped} guru dilewati karena sudah memiliki slip gaji untuk periode ini.";
        }

        return redirect()->route('bendahara.slip-gaji.index', ['month' => $month, 'year' => $year])
            ->with('success', $message);
    }

    /**
     * Show a single salary slip
     */
    public function show(SlipGaji $slipGaji)
    {
        $slipGaji->load(['user', 'creator']);
        return view('bendahara.slip-gaji.show', compact('slipGaji'));
    }

    /**
     * Update the status of a salary slip
     */
    public function updateStatus(Request $request, SlipGaji $slipGaji)
    {
        $request->validate([
            'status' => 'required|in:draft,approved,paid',
        ]);

        $slipGaji->status = $request->status;

        if ($request->status === 'approved' && !$slipGaji->approved_at) {
            $slipGaji->approved_at = now();
        }

        if ($request->status === 'paid' && !$slipGaji->paid_at) {
            $slipGaji->paid_at = now();
        }

        $slipGaji->save();

        return redirect()->back()->with('success', 'Status slip gaji berhasil diperbarui.');
    }

    /**
     * Delete a salary slip
     */
    public function destroy(SlipGaji $slipGaji)
    {
        if ($slipGaji->status !== 'draft') {
            return redirect()->back()->with('error', 'Hanya slip gaji dengan status draft yang dapat dihapus.');
        }

        $slipGaji->delete();

        return redirect()->back()->with('success', 'Slip gaji berhasil dihapus.');
    }

    /**
     * Print salary slip as PDF
     */
    public function print(SlipGaji $slipGaji)
    {
        $slipGaji->load(['user', 'creator']);

        $pdf = Pdf::loadView('bendahara.slip-gaji.pdf.slip', compact('slipGaji'))
            ->setPaper('a4', 'portrait');

        $filename = 'Slip-Gaji-' . $slipGaji->user->name . '-' . Carbon::create()->month($slipGaji->month)->isoFormat('MMMM') . '-' . $slipGaji->year . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate all slips for a month
     */
    public function generateAll(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'rate_per_hour' => 'required|numeric|min:0',
        ]);

        $month = $request->month;
        $year = $request->year;
        $ratePerHour = $request->rate_per_hour;

        // Get all teachers
        $teachers = User::where('role', 'guru')->get();

        $created = 0;
        $skipped = 0;

        foreach ($teachers as $teacher) {
            // Check if slip already exists
            $exists = SlipGaji::where('user_id', $teacher->id)
                ->where('month', $month)
                ->where('year', $year)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            // Get total teaching hours
            $totalHours = Attendance::where('user_id', $teacher->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('teaching_hours');

            $totalAmount = $totalHours * $ratePerHour;

            SlipGaji::create([
                'user_id' => $teacher->id,
                'created_by' => auth()->id(),
                'month' => $month,
                'year' => $year,
                'total_teaching_hours' => $totalHours,
                'rate_per_hour' => $ratePerHour,
                'total_amount' => $totalAmount,
                'status' => 'draft',
            ]);

            $created++;
        }

        $message = "Berhasil membuat {$created} slip gaji.";
        if ($skipped > 0) {
            $message .= " {$skipped} guru dilewati karena sudah memiliki slip gaji untuk periode ini.";
        }

        return redirect()->route('bendahara.slip-gaji.index', ['month' => $month, 'year' => $year])
            ->with('success', $message);
    }
}
