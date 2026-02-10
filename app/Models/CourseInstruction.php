<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseInstruction extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_year_id',
        'instructor_id',
        'title',
        'description',
        'file_path',
        'is_task',
        'deadline',
        'allowed_response_type',
        'class_type',
    ];

    protected $casts = [
        'is_task' => 'boolean',
        'deadline' => 'datetime',
    ];

    /**
     * Get the training year this instruction belongs to.
     */
    public function trainingYear()
    {
        return $this->belongsTo(TrainingYear::class);
    }

    /**
     * Get the instructor who created this instruction.
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get all submissions for this instruction.
     */
    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    /**
     * Check if deadline has passed.
     */
    public function isDeadlinePassed(): bool
    {
        if (!$this->is_task || !$this->deadline) {
            return false;
        }
        return now()->gt($this->deadline);
    }

    /**
     * Get submission by student.
     */
    public function getSubmissionByStudent($studentId)
    {
        return $this->submissions()->where('student_id', $studentId)->first();
    }

    /**
     * Get file URL if file exists.
     */
    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }
}
