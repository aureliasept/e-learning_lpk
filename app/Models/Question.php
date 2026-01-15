<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'image_url',
        'audio_url',
        'explanation',
        'order',
    ];

    /**
     * Get the quiz this question belongs to.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get all options for this question.
     */
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    /**
     * Get the correct option for this question.
     */
    public function correctOption()
    {
        return $this->options()->where('is_correct', true)->first();
    }
}
