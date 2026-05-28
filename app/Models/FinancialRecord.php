<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialRecord extends Model
{
    protected $fillable = [
        'created_by',
        'record_date',
        'type',
        'category',
        'description',
        'amount',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'record_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessors
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'income' => 'Pemasukan',
            'expense' => 'Pengeluaran',
            default => '-',
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'income' => 'green',
            'expense' => 'red',
            default => 'gray',
        };
    }

    // Scopes
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeForMonth($query, $month, $year)
    {
        return $query->whereMonth('record_date', $month)->whereYear('record_date', $year);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('record_date', 'desc')->orderBy('created_at', 'desc');
    }
}
