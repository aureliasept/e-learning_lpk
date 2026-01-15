<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'description',
        'level', 
        // 'price', <--- HAPUS INI
        'cover_image',
        'teacher_id',
        'category',
    ];

    public function materials()
    {
        return $this->hasMany(Material::class)->orderBy('id', 'asc');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}