<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetLog;
use Illuminate\Http\Request;

class OutboxController extends Controller
{
    /**
     * Display password reset outbox
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        
        $query = PasswordResetLog::with(['user', 'resetByUser'])
            ->orderBy('created_at', 'desc');

        if ($filter === 'unread') {
            $query->unread()->valid();
        } elseif ($filter === 'read') {
            $query->where('is_read', true);
        } elseif ($filter === 'expired') {
            $query->where('expires_at', '<', now());
        }

        $logs = $query->paginate(15);

        // Count stats
        $stats = [
            'total' => PasswordResetLog::count(),
            'unread' => PasswordResetLog::unread()->valid()->count(),
            'read' => PasswordResetLog::where('is_read', true)->count(),
            'expired' => PasswordResetLog::where('expires_at', '<', now())->count(),
        ];

        return view('admin.outbox.index', compact('logs', 'filter', 'stats'));
    }

    /**
     * Mark a log as read and show password
     */
    public function show(PasswordResetLog $outbox)
    {
        if (!$outbox->is_read && !$outbox->isExpired()) {
            $outbox->markAsRead();
        }

        return view('admin.outbox.show', compact('outbox'));
    }

    /**
     * Delete a log
     */
    public function destroy(PasswordResetLog $outbox)
    {
        $outbox->delete();

        return redirect()->route('admin.outbox.index')
            ->with('success', 'Log password reset berhasil dihapus.');
    }

    /**
     * Clean up expired logs
     */
    public function cleanup()
    {
        $deleted = PasswordResetLog::cleanupExpired();

        return redirect()->route('admin.outbox.index')
            ->with('success', "Berhasil membersihkan {$deleted} log yang kadaluarsa.");
    }

    /**
     * Mark all as read
     */
    public function markAllRead()
    {
        PasswordResetLog::unread()->valid()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return redirect()->route('admin.outbox.index')
            ->with('success', 'Semua log ditandai sudah dibaca.');
    }
}
