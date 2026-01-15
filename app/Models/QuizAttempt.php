<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'student_id',
        'answers',
        'marked_for_review',
        'score',
        'total_correct',
        'started_at',
        'finished_at',
        'status',
    ];

    protected $casts = [
        'answers' => 'array',
        'marked_for_review' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * Get the quiz this attempt belongs to.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the student who made this attempt.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Check if attempt is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if student passed.
     */
    public function isPassed(): bool
    {
        return $this->score >= $this->quiz->passing_score;
    }
}
