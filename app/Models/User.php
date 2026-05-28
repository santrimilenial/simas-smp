<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'niy',
        'phone',
        'avatar',
        'address',
        'position',
        'join_year',
    ];

    /**
     * Get avatar URL or default
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=3b82f6&color=fff&size=200';
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function teachingLogs()
    {
        return $this->hasMany(TeachingLog::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function scans()
    {
        return $this->hasMany(Scan::class);
    }

    public function slipGaji()
    {
        return $this->hasMany(SlipGaji::class);
    }

    // Attendance helpers
    public function todayAttendance()
    {
        return $this->attendances()->whereDate('date', today())->first();
    }

    public function hasCheckedInToday(): bool
    {
        return $this->attendances()->whereDate('date', today())->exists();
    }

    // Helper Methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isBendahara(): bool
    {
        return $this->role === 'bendahara';
    }

    // Accessors
    public function getYearsOfServiceAttribute(): ?int
    {
        if (!$this->join_year) {
            return null;
        }
        return now()->year - $this->join_year;
    }

    // Scopes
    public function scopeGuru($query)
    {
        return $query->where('role', 'guru');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeStaff($query)
    {
        return $query->where('role', 'staff');
    }

    public function scopeBendahara($query)
    {
        return $query->where('role', 'bendahara');
    }
}