<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\CourseInstruction;
use App\Models\Module;
use App\Models\News;
use App\Models\Teacher;
use App\Models\TrainingYear;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        // Get latest training year
        $activeYear = TrainingYear::orderByDesc('name')->first();

        // Get stats
        $stats = [
            'total_modules' => Module::when($activeYear, function ($q) use ($activeYear) {
                $q->where('training_year_id', $activeYear->id);
            })->count(),
            'total_instructions' => CourseInstruction::where('instructor_id', $user->id)
                ->when($activeYear, function ($q) use ($activeYear) {
                    $q->where('training_year_id', $activeYear->id);
                })->count(),
        ];

        // Get recent instructions by this instructor
        $recentInstructions = CourseInstruction::where('instructor_id', $user->id)
            ->with('trainingYear')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get published news (latest 6)
        $news = News::where('is_active', true)
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();

        return view('instructor.dashboard', compact(
            'user',
            'teacher',
            'activeYear',
            'stats',
            'recentInstructions',
            'news'
        ));
    }
}