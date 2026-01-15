<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    // Kita buka semua kolom agar bisa diisi (aman karena kita validasi di Controller)
    protected $guarded = [];

    // RELASI 1: Bab ini milik siapa? (Milik Modul)
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function getInstructionAttribute(): ?string
    {
        if (array_key_exists('instruction', $this->attributes)) {
            return $this->attributes['instruction'];
        }

        return $this->attributes['content'] ?? null;
    }

    // RELASI 2: Bab ini punya apa saja? (Punya banyak Tugas Siswa)
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    // RELASI 3: Bab ini ada diskusinya ga? (Punya banyak Komentar)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}