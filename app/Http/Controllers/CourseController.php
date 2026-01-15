<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Halaman Depan / Landing Page
     */
    public function index()
    {
        // --- PERBAIKAN DI SINI ---
        // Ganti 'get()' menjadi 'paginate(6)'
        // Angka 6 artinya: Tampilkan 6 kursus per halaman.
        // Ini biar error 'links does not exist' HILANG.
        $courses = Course::latest()->paginate(6); 

        // Cek News (Anti Error jika tabel belum ada)
        $latestNews = [];
        if (class_exists(\App\Models\News::class)) {
            // Ambil berita terbaru (ini tetap get() gapapa karena cuma ambil 3 biji)
            $latestNews = \App\Models\News::latest()->take(3)->get();
        }

        return view('courses.index', compact('courses', 'latestNews'));
    }

    /**
     * Halaman Detail Kursus
     */
    public function show(Course $course)
    {
        // Load materi dan tugas
        $course->load(['materials', 'assignments']); 
        
        return view('courses.show', compact('course'));
    }
}