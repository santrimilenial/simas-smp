<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tujuanPembelajarans()
    {
        return $this->hasMany(TujuanPembelajaran::class, 'subject', 'name')
            ->where('user_id', $this->user_id);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByGuru($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Static method untuk mendapatkan daftar mata pelajaran aktif
    public static function getActiveSubjectsForGuru($userId)
    {
        return static::where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');
    }
}
