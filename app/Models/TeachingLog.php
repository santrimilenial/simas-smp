<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TeachingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'academic_year_id',
        'subject',
        'class',
        'meeting_number',
        'tp',
        'material',
        'time_slot',
        'notes',
        'log_date',
    ];

    protected function casts(): array
    {
        return [
            'log_date' => 'date',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // Scopes
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('log_date', $date);
    }

    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('log_date', [$start, $end]);
    }

    public function scopeByGuru($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByClass($query, $class)
    {
        return $query->where('class', $class);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('log_date', 'desc')->orderBy('created_at', 'desc');
    }

    // Accessor
    protected $appends = ['log_date_formatted'];

    public function getLogDateFormattedAttribute()
    {
        return $this->log_date ? $this->log_date->format('Y-m-d') : null;
    }
}