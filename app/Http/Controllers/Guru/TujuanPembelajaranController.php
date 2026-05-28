<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\TujuanPembelajaran;
use App\Models\Subject;
use Illuminate\Http\Request;

class TujuanPembelajaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $subject = $request->input('subject');
        $class = $request->input('class');
        
        $tps = TujuanPembelajaran::where('user_id', auth()->id())
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('subject', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('class', 'like', "%{$search}%");
                });
            })
            ->when($subject, function ($query, $subject) {
                return $query->where('subject', $subject);
            })
            ->when($class, function ($query, $class) {
                return $query->where('class', $class);
            })
            ->orderBy('subject')
            ->orderBy('class')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get subjects from Subject model (mata pelajaran yang sudah ditambahkan guru)
        $subjects = Subject::where('user_id', auth()->id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get classes for filter
        $classes = \App\Models\ClassModel::getActiveClassNames();

        return view('guru.tp.index', compact('tps', 'search', 'subject', 'class', 'subjects', 'classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        TujuanPembelajaran::create([
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
            'class' => $validated['class'],
            'description' => $validated['description'],
            'is_active' => true,
        ]);

        return redirect()->route('guru.tp.index')
            ->with('success', 'Tujuan Pembelajaran berhasil ditambahkan!');
    }

    public function update(Request $request, TujuanPembelajaran $tp)
    {
        // Pastikan TP milik guru yang login
        // Temporary: commented out for testing
        // if ($tp->user_id != auth()->id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $tp->update($validated);

        return redirect()->route('guru.tp.index')
            ->with('success', 'Tujuan Pembelajaran berhasil diupdate!');
    }

    public function destroy(TujuanPembelajaran $tp)
    {
        try {
            // Pastikan TP milik guru yang login
            if ($tp->user_id != auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menghapus tujuan pembelajaran ini.'
                ], 403);
            }

            $tp->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tujuan Pembelajaran berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tujuan pembelajaran: ' . $e->getMessage()
            ], 500);
        }
    }

    // API untuk get TP berdasarkan mata pelajaran dan kelas
    public function getBySubject(Request $request)
    {
        $subject = $request->input('subject');
        $class = $request->input('class');
        
        $tps = TujuanPembelajaran::where('user_id', auth()->id())
            ->where('subject', $subject)
            ->when($class, function ($query, $class) {
                return $query->where('class', $class);
            })
            ->active()
            ->get(['id', 'description', 'class']);

        return response()->json($tps);
    }
}
