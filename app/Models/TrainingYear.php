<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all batches for this training year.
     */
    public function batches()
    {
        return $this->hasMany(TrainingBatch::class);
    }

    /**
     * Get all students through batches.
     */
    public function students()
    {
        return $this->hasManyThrough(Student::class, TrainingBatch::class);
    }

    /**
     * Scope to get only active training year.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get count of batches.
     */
    public function getBatchCountAttribute(): int
    {
        return $this->batches()->count();
    }

    /**
     * Get count of students across all batches.
     */
    public function getStudentCountAttribute(): int
    {
        return $this->students()->count();
    }
}
