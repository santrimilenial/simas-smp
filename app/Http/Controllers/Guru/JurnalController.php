<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\TeachingLog;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Http\Requests\StoreJurnalRequest;
use App\Http\Requests\UpdateJurnalRequest;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
  public function index(Request $request)
{
    $guru = auth()->user();
    
    $search = $request->input('search');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $filterClass = $request->input('class');
    
    $jurnals = TeachingLog::where('user_id', auth()->id())
        ->with('academicYear')
        ->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('class', 'like', "%{$search}%")
                  ->orWhere('material', 'like', "%{$search}%")
                  ->orWhere('tp', 'like', "%{$search}%");
            });
        })
        ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            return $query->dateRange($startDate, $endDate);
        })
        ->when($filterClass, function ($query, $filterClass) {
            return $query->byClass($filterClass);
        })
        ->recent()
        ->paginate(10);

    $classes = \App\Models\ClassModel::getActiveClassNames();
    
    // Get subjects for dropdown
    $subjects = Subject::where('user_id', auth()->id())
        ->where('is_active', true)
        ->orderBy('name')
        ->get();
    
    // Get academic years for dropdown
    $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
    $activeAcademicYear = AcademicYear::where('is_active', true)->first();

    return view('guru.jurnal.index', compact('guru', 'jurnals', 'search', 'startDate', 'endDate', 'filterClass', 'classes', 'subjects', 'academicYears', 'activeAcademicYear'));
}

    public function create()
    {
        // Redirect ke index karena form create sudah menggunakan modal
        return redirect()->route('guru.jurnal.index')->with('openCreateModal', true);
    }

    public function store(StoreJurnalRequest $request)
    {
        TeachingLog::create(
            $request->safe()->merge(['user_id' => auth()->id()])->toArray()
        );

        return redirect()->route('guru.jurnal.index')
            ->with('success', 'Jurnal mengajar berhasil ditambahkan!');
    }

    public function show(TeachingLog $jurnal)
    {
        // Pastikan jurnal milik guru yang login
        abort_unless($jurnal->user_id === auth()->id(), 403);
        
        // Eager load relationship
        $jurnal->load('academicYear');

        return view('guru.jurnal.show', compact('jurnal'));
    }

    public function edit(TeachingLog $jurnal)
    {
        // Pastikan jurnal milik guru yang login
        abort_unless($jurnal->user_id === auth()->id(), 403);

        $classes = config('classes.list');
        return view('guru.jurnal.edit', compact('jurnal', 'classes'));
    }

    public function update(UpdateJurnalRequest $request, TeachingLog $jurnal)
    {
        // Pastikan jurnal milik guru yang login
        abort_unless($jurnal->user_id === auth()->id(), 403);

        $jurnal->update($request->safe()->only([
            'academic_year_id',
            'subject',
            'class',
            'meeting_number',
            'tp',
            'material',
            'time_slot',
            'notes',
            'log_date',
        ]));

        return redirect()->route('guru.jurnal.index')
            ->with('success', 'Jurnal mengajar berhasil diupdate!');
    }

    public function destroy(TeachingLog $jurnal)
    {
        // Pastikan jurnal milik guru yang login
        abort_unless($jurnal->user_id === auth()->id(), 403);

        $jurnal->delete();

        // Jika request AJAX, return JSON
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jurnal mengajar berhasil dihapus!'
            ]);
        }

        return redirect()->route('guru.jurnal.index')
            ->with('success', 'Jurnal mengajar berhasil dihapus!');
    }
}