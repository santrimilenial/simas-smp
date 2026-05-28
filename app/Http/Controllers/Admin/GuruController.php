<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetLog;
use App\Http\Requests\StoreGuruRequest;
use App\Http\Requests\UpdateGuruRequest;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $gurus = User::guru()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('niy', 'like', "%{$search}%");
            })
            ->withCount('teachingLogs')
            ->latest()
            ->paginate(10);

        return view('admin.guru.index', compact('gurus', 'search'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(StoreGuruRequest $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'niy' => $request->niy,
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
            'join_year' => $request->join_year,
            'password' => bcrypt($request->password),
            'role' => 'guru',
        ];

        // If admin checks "auto verify", set email_verified_at
        if ($request->boolean('auto_verify')) {
            $data['email_verified_at'] = now();
        }

        User::create($data);

       return redirect()
        ->route('admin.guru.index', ['page' => 1])
        ->with('success', 'Guru berhasil ditambahkan!');

    }

    public function show(User $guru)
    {
        $guru->loadCount('teachingLogs');
        $logs = $guru->teachingLogs()->latest()->paginate(10);
        
        return view('admin.guru.show', compact('guru', 'logs'));
    }

    public function edit(User $guru)
    {
        return response()->json($guru);
    }

    public function update(UpdateGuruRequest $request, User $guru)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'niy' => $request->niy,
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
            'join_year' => $request->join_year,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // Handle manual email verification by admin
        if ($request->boolean('verify_email') && !$guru->email_verified_at) {
            $data['email_verified_at'] = now();
        }

        $guru->update($data);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diupdate!');
    }

    public function destroy(User $guru)
    {
        $guru->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Guru berhasil dihapus!');
    }

    public function resetPassword(User $guru)
    {
        // Generate random password yang aman (8 karakter alphanumeric)
        $newPassword = \Illuminate\Support\Str::random(8);
        
        $guru->update([
            'password' => bcrypt($newPassword)
        ]);

        // Simpan ke outbox agar bisa dilihat nanti
        PasswordResetLog::createLog(
            $guru->id,
            auth()->id(),
            $newPassword,
            24 // Kadaluarsa dalam 24 jam
        );

        return redirect()->route('admin.guru.index')
            ->with('success', "Password guru {$guru->name} berhasil direset. Lihat password baru di menu Outbox.");
    }
}