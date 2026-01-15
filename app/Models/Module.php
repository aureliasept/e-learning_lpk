<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Chapter;

class Module extends Model
{
    use HasFactory;

    // Izinkan kolom-kolom ini diisi
    protected $fillable = [
        'course_id',
        'training_batch_id',
        'title',
        'subtitle',
        'description',
        'file_path',
        'file_type',
        'class_type',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function trainingBatch()
    {
        return $this->belongsTo(TrainingBatch::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}