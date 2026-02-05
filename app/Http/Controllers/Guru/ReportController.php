<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\TeachingLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JurnalExport;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        $filterClass = $request->input('class');

        $logs = TeachingLog::where('user_id', auth()->id())
            ->when($filterClass, fn($q) => $q->byClass($filterClass))
            ->dateRange($startDate, $endDate)
            ->recent()
            ->paginate(20);

        $classes = \App\Models\ClassModel::getActiveClassNames();

        return view('guru.reports.index', compact('logs', 'startDate', 'endDate', 'filterClass', 'classes'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        $filterClass = $request->input('class');

        $logs = TeachingLog::with(['user', 'academicYear'])
            ->where('user_id', auth()->id())
            ->when($filterClass, fn($q) => $q->byClass($filterClass))
            ->dateRange($startDate, $endDate)
            ->recent()
            ->get();

        $pdf = Pdf::loadView('guru.reports.pdf', compact('logs', 'startDate', 'endDate', 'filterClass'));
        
        $filename = 'jurnal-saya-' . Carbon::now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        $filterClass = $request->input('class');

        $filename = 'jurnal-saya-' . Carbon::now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(
            new JurnalExport($startDate, $endDate, auth()->id(), $filterClass),
            $filename
        );
    }
}