<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $academicYears = AcademicYear::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('admin.academic-years.index', compact('academicYears', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama tahun ajaran harus diisi',
            'semester.required' => 'Semester harus dipilih',
            'semester.in' => 'Semester harus Ganjil atau Genap',
            'start_date.required' => 'Tanggal mulai harus diisi',
            'end_date.required' => 'Tanggal selesai harus diisi',
            'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai',
        ]);

        // Cek duplikasi name + semester
        $exists = AcademicYear::where('name', $validated['name'])
            ->where('semester', $validated['semester'])
            ->exists();
        
        if ($exists) {
            return redirect()->back()->withErrors(['name' => 'Tahun ajaran dengan semester ini sudah ada'])->withInput();
        }

        // Jika is_active true, nonaktifkan yang lain
        if ($request->has('is_active') && $request->is_active) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        AcademicYear::create([
            'name' => $validated['name'],
            'semester' => $validated['semester'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan!');
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama tahun ajaran harus diisi',
            'semester.required' => 'Semester harus dipilih',
            'semester.in' => 'Semester harus Ganjil atau Genap',
            'start_date.required' => 'Tanggal mulai harus diisi',
            'end_date.required' => 'Tanggal selesai harus diisi',
            'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai',
        ]);

        // Cek duplikasi name + semester (exclude current)
        $exists = AcademicYear::where('name', $validated['name'])
            ->where('semester', $validated['semester'])
            ->where('id', '!=', $academicYear->id)
            ->exists();
        
        if ($exists) {
            return redirect()->back()->withErrors(['name' => 'Tahun ajaran dengan semester ini sudah ada'])->withInput();
        }

        // Jika is_active true, nonaktifkan yang lain
        if ($request->has('is_active') && $request->is_active) {
            AcademicYear::where('is_active', true)
                ->where('id', '!=', $academicYear->id)
                ->update(['is_active' => false]);
        }

        $academicYear->update([
            'name' => $validated['name'],
            'semester' => $validated['semester'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil diupdate!');
    }

    public function destroy(AcademicYear $academicYear)
    {
        // Cek apakah ada jurnal yang menggunakan tahun ajaran ini
        if ($academicYear->teachingLogs()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tahun ajaran tidak dapat dihapus karena masih digunakan oleh jurnal mengajar!'
            ], 400);
        }

        $academicYear->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tahun ajaran berhasil dihapus!'
        ]);
    }

    public function setActive(AcademicYear $academicYear)
    {
        // Nonaktifkan semua tahun ajaran
        AcademicYear::where('is_active', true)->update(['is_active' => false]);
        
        // Aktifkan yang dipilih
        $academicYear->update(['is_active' => true]);

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran ' . $academicYear->full_name . ' berhasil diaktifkan!');
    }
}
