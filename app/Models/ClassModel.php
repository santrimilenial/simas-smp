<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    // Cache keys
    const CACHE_KEY_ACTIVE_NAMES = 'class_active_names';
    const CACHE_TTL = 3600; // 1 jam

    protected $fillable = [
        'name',
        'grade_level',
        'class_group',
        'student_count',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'student_count' => 'integer',
            'order' => 'integer',
        ];
    }

    // Relationships
    public function teachingLogs()
    {
        return $this->hasMany(TeachingLog::class, 'class', 'name');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    public function scopeByGradeLevel($query, $level)
    {
        return $query->where('grade_level', $level);
    }

    // Helpers (with Cache)
    public static function getActiveClassNames()
    {
        return Cache::remember(self::CACHE_KEY_ACTIVE_NAMES, self::CACHE_TTL, function () {
            return static::active()->ordered()->pluck('name')->toArray();
        });
    }

    // Clear cache
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY_ACTIVE_NAMES);
    }

    // Override save to clear cache
    public function save(array $options = [])
    {
        $result = parent::save($options);
        self::clearCache();
        return $result;
    }

    // Override delete to clear cache
    public function delete()
    {
        $result = parent::delete();
        self::clearCache();
        return $result;
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->student_count > 0) {
            return "{$this->name} ({$this->student_count} siswa)";
        }
        return $this->name;
    }
}