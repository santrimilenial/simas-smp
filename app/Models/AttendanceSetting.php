<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AttendanceSetting extends Model
{
    protected $fillable = [
        'work_start',
        'late_time',
        'work_end',
        'grace_period',
        'allow_early_checkin',
        'is_active',
        'working_days_per_month',
    ];

    protected $casts = [
        'allow_early_checkin' => 'boolean',
        'is_active' => 'boolean',
        'grace_period' => 'integer',
    ];

    // Cache key constant
    const CACHE_KEY = 'attendance_setting_current';
    const CACHE_TTL = 3600; // 1 jam

    // Static Method - Get Current Active Setting (with Cache)
    public static function current(): self
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return self::where('is_active', true)->first() ?? self::first() ?? self::create([
                'work_start' => '07:00:00',
                'late_time' => '07:15:00',
                'work_end' => '16:00:00',
                'grace_period' => 5,
                'is_active' => true,
            ]);
        });
    }

    // Clear cache when settings updated
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
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

    // Accessors
    public function getFormattedCheckInTimeAttribute(): string
    {
        if (!$this->work_start) return '07:00';
        
        // Parse as time string (HH:MM:SS)
        if (is_string($this->work_start)) {
            return substr($this->work_start, 0, 5); // Get HH:MM from HH:MM:SS
        }
        
        if ($this->work_start instanceof \DateTime) {
            return $this->work_start->format('H:i');
        }
        
        return Carbon::parse($this->work_start)->format('H:i');
    }

    public function getFormattedLateTimeAttribute(): string
    {
        if (!$this->late_time) return '07:15';
        
        // Parse as time string (HH:MM:SS)
        if (is_string($this->late_time)) {
            return substr($this->late_time, 0, 5); // Get HH:MM from HH:MM:SS
        }
        
        if ($this->late_time instanceof \DateTime) {
            return $this->late_time->format('H:i');
        }
        
        return Carbon::parse($this->late_time)->format('H:i');
    }

    public function getFormattedCheckOutTimeAttribute(): string
    {
        if (!$this->work_end) return '16:00';
        
        // Parse as time string (HH:MM:SS)
        if (is_string($this->work_end)) {
            return substr($this->work_end, 0, 5); // Get HH:MM from HH:MM:SS
        }
        
        if ($this->work_end instanceof \DateTime) {
            return $this->work_end->format('H:i');
        }
        
        return Carbon::parse($this->work_end)->format('H:i');
    }

    public function getActualLateTimeAttribute(): Carbon
    {
        // Late time + grace period
        $lateTime = $this->late_time;
        
        // Pastikan late_time tidak null
        if (!$lateTime) {
            $lateTime = '07:15:00';
        }
        
        // Convert ke Carbon jika belum
        if (!($lateTime instanceof Carbon)) {
            $lateTime = Carbon::parse($lateTime);
        }
        
        return $lateTime->copy()->addMinutes($this->grace_period ?? 0);
    }

    // Methods
    public function isLate(Carbon $time = null): bool
    {
        $time = $time ?? now();
        $actualLateTime = $this->actual_late_time;
        
        // Compare only time (ignore date)
        $checkTime = Carbon::parse($time->format('H:i:s'));
        $lateTime = Carbon::parse($actualLateTime->format('H:i:s'));
        
        return $checkTime->greaterThanOrEqualTo($lateTime);
    }

    public function isWorkingDay(Carbon $date = null): bool
    {
        // For now, assume all days are working days (since working_days is not in DB)
        return true;
    }

    public function canCheckIn(Carbon $time = null): bool
    {
        $time = $time ?? now();
        
        // Check if it's a working day
        if (!$this->isWorkingDay($time)) {
            return false;
        }

        // Check if early check-in is allowed
        if (!$this->allow_early_checkin) {
            // Parse work_start as string to Carbon
            $checkInTime = $this->work_start 
                ? Carbon::parse($this->work_start) 
                : Carbon::parse('07:00:00');
            $currentTime = Carbon::parse($time->format('H:i:s'));
            
            return $currentTime->greaterThanOrEqualTo($checkInTime);
        }

        return true;
    }

    public function getStatus(Carbon $time = null): string
    {
        $time = $time ?? now();
        
        if (!$this->isWorkingDay($time)) {
            return 'not_working_day';
        }

        if ($this->isLate($time)) {
            return 'late';
        }

        return 'on_time';
    }

    public function getWorkingDaysCount($month = null, $year = null): int
    {
        // Return the configured working days per month from settings
        return $this->working_days_per_month ?? 22;
    }

    // Validation Rules
    public static function validationRules(): array
    {
        return [
            'check_in_time' => 'required|date_format:H:i',
            'late_time' => 'required|date_format:H:i|after:check_in_time',
            'check_out_time' => 'required|date_format:H:i|after:late_time',
            'grace_period' => 'required|integer|min:0|max:30',
            'working_days' => 'required|array|min:1',
            'working_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'allow_early_checkin' => 'boolean',
            'require_late_notes' => 'boolean',
            'auto_checkout' => 'boolean',
        ];
    }
}