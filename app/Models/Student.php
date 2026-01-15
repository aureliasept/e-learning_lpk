<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nis',
        'phone',
        'classroom',
        'type', // reguler / karyawan
        'academic_year_id',
        'training_batch_id',
        'entry_date',
        'gender',
        'address',
        'birth_place',
        'birth_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'entry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Legacy relationship - kept for backward compatibility.
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * New relationship - Training Batch (Gelombang).
     */
    public function trainingBatch()
    {
        return $this->belongsTo(TrainingBatch::class);
    }

    /**
     * Get training year through batch.
     */
    public function trainingYear()
    {
        return $this->trainingBatch?->trainingYear;
    }
}