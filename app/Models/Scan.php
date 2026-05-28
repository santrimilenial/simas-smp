<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'scanned_at',
        'scan_type',
        'location',
        'notes',
        'condition_report',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    // Relationship dengan item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relationship dengan user (staff)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk filter by date range
    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('scanned_at', [$start, $end]);
    }

    // Scope untuk filter by staff
    public function scopeByStaff($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
