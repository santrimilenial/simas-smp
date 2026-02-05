<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AcademicYear extends Model
{
    use HasFactory;

    // Cache keys
    const CACHE_KEY_ACTIVE = 'academic_year_active';
    const CACHE_KEY_ALL = 'academic_year_all';
    const CACHE_TTL = 3600; // 1 jam

    protected $fillable = [
        'name',
        'semester',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // Accessor untuk nama lengkap dengan semester
    public function getFullNameAttribute()
    {
        return $this->name . ' ' . ucfirst($this->semester);
    }

    // Relationships
    public function teachingLogs()
    {
        return $this->hasMany(TeachingLog::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Static methods (with Cache)
    public static function getActive()
    {
        return Cache::remember(self::CACHE_KEY_ACTIVE, self::CACHE_TTL, function () {
            return static::where('is_active', true)->first();
        });
    }

    public static function getAllActive()
    {
        return Cache::remember(self::CACHE_KEY_ALL, self::CACHE_TTL, function () {
            return static::orderBy('start_date', 'desc')->get();
        });
    }

    // Clear cache
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY_ACTIVE);
        Cache::forget(self::CACHE_KEY_ALL);
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

    // Accessor
    public function getFormattedPeriodAttribute()
    {
        return $this->start_date->format('d M Y') . ' - ' . $this->end_date->format('d M Y');
    }
}
