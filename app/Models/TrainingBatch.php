<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_year_id',
        'name',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the training year that owns this batch.
     */
    public function year()
    {
        return $this->belongsTo(TrainingYear::class, 'training_year_id');
    }

    /**
     * Alias for year() relationship.
     */
    public function trainingYear()
    {
        return $this->belongsTo(TrainingYear::class, 'training_year_id');
    }

    /**
     * Get all students in this batch.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the status based on dates.
     * Returns: 'selesai' | 'aktif' | 'segera'
     */
    public function getStatusAttribute(): string
    {
        $today = Carbon::today();

        if ($this->end_date && $this->end_date->lt($today)) {
            return 'selesai';
        }

        if ($this->start_date && $this->end_date) {
            if ($today->between($this->start_date, $this->end_date)) {
                return 'aktif';
            }
        }

        if ($this->start_date && $this->start_date->gt($today)) {
            return 'segera';
        }

        return 'aktif'; // default
    }

    /**
     * Get human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'selesai' => 'Selesai',
            'aktif' => 'Aktif',
            'segera' => 'Segera Datang',
            default => 'Aktif',
        };
    }

    /**
     * Get status badge color classes.
     */
    public function getStatusColorAttribute(): array
    {
        return match($this->status) {
            'selesai' => [
                'bg' => 'bg-gray-500/10',
                'text' => 'text-gray-400',
                'border' => 'border-gray-500/30',
            ],
            'aktif' => [
                'bg' => 'bg-green-500/10',
                'text' => 'text-green-400',
                'border' => 'border-green-500/30',
            ],
            'segera' => [
                'bg' => 'bg-blue-500/10',
                'text' => 'text-blue-400',
                'border' => 'border-blue-500/30',
            ],
            default => [
                'bg' => 'bg-gray-500/10',
                'text' => 'text-gray-400',
                'border' => 'border-gray-500/30',
            ],
        };
    }

    /**
     * Get formatted date range.
     */
    public function getDateRangeAttribute(): string
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->format('d M') . ' - ' . $this->end_date->format('d M Y');
        }
        return '-';
    }

    /**
     * Get count of students.
     */
    public function getStudentCountAttribute(): int
    {
        return $this->students()->count();
    }
}
