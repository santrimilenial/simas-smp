<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'check_in_time',
        'check_out_time',
        'status',
        'reason',
        'notes',
        'late_minutes',
        'check_in_status',
        'check_in_reason',
        'check_out_status',
        'check_out_reason',
        'teaching_hours',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_latitude',
        'check_out_longitude',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'late_minutes' => 'integer',
        'teaching_hours' => 'integer',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getFormattedCheckInAttribute(): ?string
    {
        return $this->check_in_time ? $this->check_in_time->format('H:i') : null;
    }

    public function getFormattedCheckOutAttribute(): ?string
    {
        return $this->check_out_time ? $this->check_out_time->format('H:i') : null;
    }

    // Location Accessors
    public function getCheckInLocationAttribute(): ?string
    {
        if ($this->check_in_latitude && $this->check_in_longitude) {
            return $this->check_in_latitude . ', ' . $this->check_in_longitude;
        }
        return null;
    }

    public function getCheckOutLocationAttribute(): ?string
    {
        if ($this->check_out_latitude && $this->check_out_longitude) {
            return $this->check_out_latitude . ', ' . $this->check_out_longitude;
        }
        return null;
    }

    public function getCheckInMapUrlAttribute(): ?string
    {
        if ($this->check_in_latitude && $this->check_in_longitude) {
            return "https://www.google.com/maps?q={$this->check_in_latitude},{$this->check_in_longitude}";
        }
        return null;
    }

    public function getCheckOutMapUrlAttribute(): ?string
    {
        if ($this->check_out_latitude && $this->check_out_longitude) {
            return "https://www.google.com/maps?q={$this->check_out_latitude},{$this->check_out_longitude}";
        }
        return null;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'present' => 'Hadir Tepat Waktu',
            'late' => 'Hadir Terlambat',
            'permission' => 'Izin',
            'sick' => 'Sakit',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'present' => 'green',
            'late' => 'yellow',
            'permission' => 'blue',
            'sick' => 'purple',
            default => 'gray',
        };
    }

    public function getCheckInStatusLabelAttribute(): string
    {
        return match($this->check_in_status) {
            'present' => 'Hadir',
            'permission' => 'Izin',
            'sick' => 'Sakit',
            null => '-',
            default => 'Unknown',
        };
    }

    public function getCheckOutStatusLabelAttribute(): string
    {
        return match($this->check_out_status) {
            'present' => 'Normal',
            'early_leave' => 'Pulang Awal',
            null => '-',
            default => 'Unknown',
        };
    }

    public function getCheckInTimeLabelAttribute(): ?string
    {
        if (!$this->check_in_time) {
            return null;
        }

        $settings = AttendanceSetting::current();
        $checkInTime = Carbon::parse($this->check_in_time);
        $lateTime = Carbon::parse($settings->actual_late_time ?? '07:00');

        if ($checkInTime->lessThanOrEqualTo($lateTime)) {
            return $this->formatted_check_in . ' (Tepat Waktu)';
        } else {
            $diffMinutes = $checkInTime->diffInMinutes($lateTime);
            return $this->formatted_check_in . " (Terlambat {$diffMinutes} menit)";
        }
    }

    public function getDurationAttribute(): ?int
    {
        if ($this->check_in_time && $this->check_out_time) {
            return $this->check_in_time->diffInHours($this->check_out_time);
        }
        return null;
    }

    public function getLateInfoAttribute(): ?string
    {
        if ($this->status === 'late' && $this->late_minutes > 0) {
            return "Terlambat {$this->late_minutes} menit";
        }
        return null;
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('date', now()->year)
                     ->whereMonth('date', now()->month);
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereYear('date', $year)
                     ->whereMonth('date', $month);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    public function scopeSick($query)
    {
        return $query->where('status', 'sick');
    }

    public function scopePermission($query)
    {
        return $query->where('status', 'permission');
    }

    // Static Methods
    public static function checkIn($userId, $attendanceStatus, $reason = null, $notes = null, $latitude = null, $longitude = null): self
    {
        $settings = AttendanceSetting::current();
        $now = now();
        
        // Determine final status and late minutes
        $status = $attendanceStatus;
        $lateMinutes = 0;
        
        // Hanya cek keterlambatan jika status adalah hadir
        if ($attendanceStatus === 'present') {
            if ($settings->isLate($now)) {
                $status = 'late';
                // Calculate late minutes
                $lateTime = $settings->actual_late_time;
                $lateMinutes = Carbon::parse($now->format('H:i:s'))
                    ->diffInMinutes(Carbon::parse($lateTime->format('H:i:s')));
            }
        }

        return self::updateOrCreate(
            [
                'user_id' => $userId,
                'date' => $now->toDateString(),
            ],
            [
                'check_in_time' => $now,
                'status' => $status,
                'check_in_status' => 'present',
                'reason' => $reason,
                'notes' => $notes,
                'late_minutes' => $lateMinutes,
                'check_in_latitude' => $latitude,
                'check_in_longitude' => $longitude,
            ]
        );
    }

    public static function checkOut($userId, $notes = null): ?self
    {
        $attendance = self::where('user_id', $userId)
                         ->whereDate('date', today())
                         ->first();

        if ($attendance && !$attendance->check_out_time) {
            $attendance->update([
                'check_out_time' => now(),
                'notes' => $notes ? ($attendance->notes ? $attendance->notes . ' | ' . $notes : $notes) : $attendance->notes,
            ]);
        }

        return $attendance;
    }

    public static function getTodayAttendance($userId): ?self
    {
        return self::where('user_id', $userId)
                   ->whereDate('date', today())
                   ->first();
    }

    public static function getMonthlyStats($userId, $month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $attendances = self::byUser($userId)
                          ->byMonth($month, $year)
                          ->get();

        $presentCount = $attendances->whereIn('status', ['present', 'late'])->count();
        $totalDays = $attendances->count();

        return [
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'permission' => $attendances->where('status', 'permission')->count(),
            'sick' => $attendances->where('status', 'sick')->count(),
            'total' => $totalDays,
            'percentage' => $totalDays > 0 
                ? ($presentCount / $totalDays) * 100 
                : 0,
        ];
    }

    // Di model Attendance
    public function getActualStatusAttribute()
    {
    // Jika check_in_status adalah permission atau sick, gunakan itu
    if (in_array($this->check_in_status, ['permission', 'sick'])) {
        return $this->check_in_status;
    }
    
    // Selain itu gunakan status default
    return $this->status;
    }

    public function getActualStatusLabelAttribute()
    {
        $labels = [
            'present' => 'Hadir Tepat Waktu',
            'late' => 'Hadir Terlambat',
            'permission' => 'Izin',
            'sick' => 'Sakit',
        ];
        
        return $labels[$this->actual_status] ?? 'Unknown';
    }

}