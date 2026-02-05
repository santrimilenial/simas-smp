<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'category',
        'description',
        'location',
        'condition',
        'quantity',
        'price',
        'purchase_date',
        'barcode_path',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Relationship dengan scans
    public function scans()
    {
        return $this->hasMany(Scan::class);
    }

    // Get latest scan
    public function latestScan()
    {
        return $this->hasOne(Scan::class)->latestOfMany('scanned_at');
    }

    // Get total scan count
    public function getTotalScansAttribute()
    {
        return $this->scans()->count();
    }
}
