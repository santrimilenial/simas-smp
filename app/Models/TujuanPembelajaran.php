<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanPembelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'class',
        'description',
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByGuru($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    public function scopeByClass($query, $class)
    {
        return $query->where('class', $class);
    }
}
