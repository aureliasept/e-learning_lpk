<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get all students for this training year.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get all teachers for this training year.
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Get count of students.
     */
    public function getStudentCountAttribute(): int
    {
        return $this->students()->count();
    }
}
