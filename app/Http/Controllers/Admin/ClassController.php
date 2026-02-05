<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Http\Requests\StoreClassRequest;
use App\Http\Requests\UpdateClassRequest;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $gradeLevel = $request->input('grade_level');
        
        $classes = ClassModel::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('class_group', 'like', "%{$search}%");
            })
            ->when($gradeLevel, function ($query, $gradeLevel) {
                return $query->byGradeLevel($gradeLevel);
            })
            ->withCount('teachingLogs')
            ->ordered()
            ->paginate(15);

        $gradeLevels = ClassModel::distinct()->orderBy('grade_level')->pluck('grade_level');

        return view('admin.classes.index', compact('classes', 'search', 'gradeLevel', 'gradeLevels'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(StoreClassRequest $request)
    {
        ClassModel::create([
            'name' => $request->name,
            'grade_level' => $request->grade_level,
            'class_group' => $request->class_group,
            'student_count' => $request->student_count,
            'is_active' => $request->boolean('is_active', true),
            'order' => $request->order ?? ClassModel::max('order') + 1,
        ]);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit(ClassModel $class)
    {
        return view('admin.classes.edit', compact('class'));
    }

    public function update(UpdateClassRequest $request, ClassModel $class)
    {
        $class->update([
            'name' => $request->name,
            'grade_level' => $request->grade_level,
            'class_group' => $request->class_group,
            'student_count' => $request->student_count,
            'is_active' => $request->boolean('is_active'),
            'order' => $request->order ?? $class->order,
        ]);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Data kelas berhasil diupdate!');
    }

    public function destroy(ClassModel $class)
    {
        // Check jika kelas punya jurnal
        if ($class->teachingLogs()->count() > 0) {
            return redirect()->route('admin.classes.index')
                ->with('error', 'Kelas tidak bisa dihapus karena masih memiliki jurnal terkait!');
        }

        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }

    public function toggleStatus(ClassModel $class)
    {
        $class->update(['is_active' => !$class->is_active]);

        $status = $class->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.classes.index')
            ->with('success', "Kelas berhasil {$status}!");
    }
}