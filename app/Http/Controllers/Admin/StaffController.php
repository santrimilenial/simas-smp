<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staff = User::where('role', 'staff')
            ->select('id', 'name', 'email', 'phone', 'created_at')
            ->latest()
            ->paginate(20);
        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => 'staff',
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $staff)
    {
        if ($staff->role !== 'staff') {
            abort(404);
        }

        $staff->load(['scans' => function($query) {
            $query->with('item')->latest()->limit(10);
        }]);

        return view('admin.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $staff)
    {
        if ($staff->role !== 'staff') {
            abort(404);
        }

        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $staff)
    {
        if ($staff->role !== 'staff') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $staff->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $staff->update($updateData);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $staff)
    {
        if ($staff->role !== 'staff') {
            abort(404);
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff berhasil dihapus!');
    }
}
