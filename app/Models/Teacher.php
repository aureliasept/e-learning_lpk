<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'phone',
        'position',
        'birth_place',
        'birth_date',
        'is_reguler',
        'is_karyawan',
        'training_year_id',
    ];

    protected $casts = [
        'is_reguler' => 'boolean',
        'is_karyawan' => 'boolean',
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the training year this instructor is assigned to.
     */
    public function trainingYear()
    {
        return $this->belongsTo(TrainingYear::class);
    }

    /**
     * Get the courses this teacher is assigned to.
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }
}