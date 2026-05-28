<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GuruInfoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $gurus = User::guru()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('niy', 'like', "%{$search}%")
                      ->orWhere('position', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15);

        return view('admin.guru-info.index', compact('gurus', 'search'));
    }
}
