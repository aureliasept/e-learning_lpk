<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_instruction_id',
        'student_id',
        'file_path',
        'text_response',
        'submitted_at',
        'grade',
        'feedback',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the instruction this submission belongs to.
     */
    public function courseInstruction()
    {
        return $this->belongsTo(CourseInstruction::class);
    }

    /**
     * Get the student who made this submission.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Check if submission has been graded.
     */
    public function isGraded(): bool
    {
        return $this->grade !== null;
    }

    /**
     * Get file URL if file exists.
     */
    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }
}
