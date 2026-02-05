<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $subjects = Subject::where('user_id', auth()->id())
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15);

        return view('guru.subjects.index', compact('subjects', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Cek apakah sudah ada mata pelajaran dengan nama yang sama
        $exists = Subject::where('user_id', auth()->id())
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return redirect()->route('guru.subjects.index')
                ->with('error', 'Mata pelajaran dengan nama tersebut sudah ada!');
        }

        Subject::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'is_active' => true,
        ]);

        return redirect()->route('guru.subjects.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    public function update(Request $request, Subject $subject)
    {
        // Pastikan subject milik guru yang login
        if ($subject->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Cek apakah sudah ada mata pelajaran dengan nama yang sama (kecuali diri sendiri)
        $exists = Subject::where('user_id', auth()->id())
            ->where('name', $validated['name'])
            ->where('id', '!=', $subject->id)
            ->exists();

        if ($exists) {
            return redirect()->route('guru.subjects.index')
                ->with('error', 'Mata pelajaran dengan nama tersebut sudah ada!');
        }

        $subject->update([
            'name' => $validated['name'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('guru.subjects.index')
            ->with('success', 'Mata pelajaran berhasil diupdate!');
    }

    public function destroy(Subject $subject)
    {
        // Pastikan subject milik guru yang login
        if ($subject->user_id !== auth()->id()) {
            abort(403);
        }

        $subject->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil dihapus!'
        ]);
    }

    // API untuk mendapatkan semua mata pelajaran aktif
    public function getActive()
    {
        $subjects = Subject::where('user_id', auth()->id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($subjects);
    }
}
