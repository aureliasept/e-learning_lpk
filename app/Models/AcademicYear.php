<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Accessor untuk mendapatkan tahun (untuk grouping tabs).
     * Prioritas: start_date, atau parsing dari nama (misal "2025/2026" → 2025).
     */
    public function getYearAttribute(): ?int
    {
        if ($this->start_date) {
            return (int) $this->start_date->format('Y');
        }
        
        // Parsing dari nama jika start_date null
        if (preg_match('/(\d{4})/', $this->name, $matches)) {
            return (int) $matches[1];
        }
        
        return null;
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
