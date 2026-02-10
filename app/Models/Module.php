<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\TrainingYear;

class Module extends Model
{
    use HasFactory;

    // Izinkan kolom-kolom ini diisi
    protected $fillable = [
        'course_id',
        'training_year_id',
        'title',
        'subtitle',
        'description',
        'file_path',
        'original_filename',
        'file_type',
        'class_type',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function trainingYear()
    {
        return $this->belongsTo(TrainingYear::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}