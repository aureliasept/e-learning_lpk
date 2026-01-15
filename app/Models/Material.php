<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 
        'title', 
        'content', 
        'order', 
        'file_path',  // Baru
        'description' // Baru
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relasi ke Tugas
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}