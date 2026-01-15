<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $query = Course::query();

        if (Schema::hasColumn('courses', 'teacher_id')) {
            $query->where('teacher_id', Auth::id());
        } elseif (Schema::hasColumn('courses', 'instruktur_id')) {
            $query->where('instruktur_id', Auth::id());
        }

        $courses = $query->latest()->get();

        return view('instructor.courses.index', compact('courses'));
    }

    public function show(int $id): View
    {
        $query = Course::query();

        if (Schema::hasColumn('courses', 'teacher_id')) {
            $query->where('teacher_id', Auth::id());
        } elseif (Schema::hasColumn('courses', 'instruktur_id')) {
            $query->where('instruktur_id', Auth::id());
        }

        $course = $query->findOrFail($id);

        $modulesQuery = Module::query();
        if (Schema::hasColumn('modules', 'course_id')) {
            $modulesQuery->where('course_id', $course->id);
        }
        $modules = $modulesQuery->orderBy('id')->get();

        $chaptersQuery = Chapter::query()->with('module');
        if (Schema::hasColumn('modules', 'course_id')) {
            $chaptersQuery->whereHas('module', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            });
        }
        $chapters = $chaptersQuery->orderBy('id')->get();

        return view('instructor.courses.show', compact('course', 'modules', 'chapters'));
    }
}
