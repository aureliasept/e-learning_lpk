<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'title',
        'instruction',
        'file_path',
        'deadline'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}