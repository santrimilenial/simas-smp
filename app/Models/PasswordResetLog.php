<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PasswordResetLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reset_by',
        'temp_password',
        'is_read',
        'read_at',
        'expires_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user whose password was reset
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who reset the password
     */
    public function resetByUser()
    {
        return $this->belongsTo(User::class, 'reset_by');
    }

    /**
     * Set encrypted password
     */
    public function setTempPasswordAttribute($value)
    {
        $this->attributes['temp_password'] = Crypt::encryptString($value);
    }

    /**
     * Get decrypted password (only if not expired and not read)
     */
    public function getDecryptedPasswordAttribute()
    {
        if ($this->isExpired()) {
            return '***KADALUARSA***';
        }

        try {
            return Crypt::decryptString($this->temp_password);
        } catch (\Exception $e) {
            return '***ERROR***';
        }
    }

    /**
     * Check if the log is expired
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Scope for unread logs
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for valid (not expired) logs
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Create a new reset log
     */
    public static function createLog($userId, $resetBy, $plainPassword, $expiresInHours = 24)
    {
        return self::create([
            'user_id' => $userId,
            'reset_by' => $resetBy,
            'temp_password' => $plainPassword,
            'expires_at' => now()->addHours($expiresInHours),
        ]);
    }

    /**
     * Clean up expired logs (can be called via scheduler)
     */
    public static function cleanupExpired()
    {
        return self::where('expires_at', '<', now())->delete();
    }
}
