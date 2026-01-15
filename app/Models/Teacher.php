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
        'training_batch_id', // NEW: Link to training batch
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
     * Get the training batch this instructor is assigned to.
     */
    public function trainingBatch()
    {
        return $this->belongsTo(TrainingBatch::class);
    }

    /**
     * Get the training year through the batch.
     */
    public function trainingYear()
    {
        return $this->trainingBatch?->trainingYear;
    }
}