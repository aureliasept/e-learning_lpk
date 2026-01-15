<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',   // KEMBALI KE USER_ID
        'course_id',
        'status',
        'enrolled_at'
    ];

    /**
     * Relasi ke Siswa (User).
     * Karena di tabel pakai user_id, relasinya ke User::class
     */
    public function student()
    {
        // Parameter kedua 'user_id' menjelaskan nama kolom foreign key-nya
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}