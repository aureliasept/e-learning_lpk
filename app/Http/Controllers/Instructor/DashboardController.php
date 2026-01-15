<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get courses for this instructor
        $courseQuery = Course::query();

        if (Schema::hasColumn('courses', 'teacher_id')) {
            $courseQuery->where('teacher_id', Auth::id());
        } elseif (Schema::hasColumn('courses', 'instruktur_id')) {
            $courseQuery->where('instruktur_id', Auth::id());
        }

        $courses = $courseQuery->latest()->get();

        // Get published news (latest 6)
        $news = News::where('is_active', true)
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();

        return view('instructor.dashboard', compact(
            'user',
            'courses',
            'news'
        ));
    }
}