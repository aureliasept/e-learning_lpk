<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration_minutes',
        'training_batch_id',
        'instructor_id',
        'passing_score',
        'is_active',
        'access_code',
        'show_answers_after',
        'shuffle_questions',
        'shuffle_options',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_answers_after' => 'boolean',
        'shuffle_questions' => 'boolean',
        'shuffle_options' => 'boolean',
    ];

    /**
     * Generate a random 6-character access code.
     */
    public static function generateAccessCode(): string
    {
        return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
    }

    /**
     * Check if quiz requires access code.
     */
    public function requiresAccessCode(): bool
    {
        return !empty($this->access_code);
    }

    /**
     * Get the instructor (user) who created this quiz.
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the training batch this quiz belongs to.
     */
    public function trainingBatch()
    {
        return $this->belongsTo(TrainingBatch::class);
    }

    /**
     * Get all questions for this quiz.
     */
    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    /**
     * Get all attempts for this quiz.
     */
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get question count.
     */
    public function getQuestionCountAttribute(): int
    {
        return $this->questions()->count();
    }
}
