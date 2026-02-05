<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class SlipGaji extends Model
{
    protected $table = 'slip_gaji';

    protected $fillable = [
        'user_id',
        'created_by',
        'month',
        'year',
        'total_teaching_hours',
        'rate_per_hour',
        'total_amount',
        'status',
        'notes',
        'approved_at',
        'paid_at',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'total_teaching_hours' => 'integer',
        'rate_per_hour' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessors
    public function getPeriodAttribute(): string
    {
        return Carbon::create($this->year, $this->month, 1)->isoFormat('MMMM YYYY');
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedRateAttribute(): string
    {
        return 'Rp ' . number_format($this->rate_per_hour, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'approved' => 'Disetujui',
            'paid' => 'Sudah Dibayar',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'yellow',
            'approved' => 'blue',
            'paid' => 'green',
            default => 'gray',
        };
    }

    // Methods
    public function calculateTotal(): void
    {
        $this->total_amount = $this->total_teaching_hours * $this->rate_per_hour;
    }

    public function approve(): void
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->save();
    }

    public function markAsPaid(): void
    {
        $this->status = 'paid';
        $this->paid_at = now();
        $this->save();
    }

    // Scopes
    public function scopeForMonth($query, int $month, int $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
