<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseInstruction;
use App\Models\News;
use App\Models\Quiz;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->with('trainingYear')->first();
        
        $yearId = $student?->training_year_id;

        // Get pending tasks (tasks not yet submitted) - filtered by training year
        $pendingTasks = 0;
        $recentInstructions = collect();
        if ($yearId) {
            $studentId = $student->id;
            $studentType = $student->type;
            
            $recentInstructions = CourseInstruction::where('training_year_id', $yearId)
                ->whereIn('class_type', [$studentType, 'all'])
                ->with(['instructor', 'submissions' => function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                }])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $pendingTasks = CourseInstruction::where('training_year_id', $yearId)
                ->whereIn('class_type', [$studentType, 'all'])
                ->where('is_task', true)
                ->where('deadline', '>', now())
                ->whereDoesntHave('submissions', function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                })
                ->count();
        }

        // Get available quizzes - filtered by training year
        $availableQuizzes = Quiz::where('is_active', true)
            ->where(function ($query) use ($yearId) {
                $query->where('training_year_id', $yearId)
                    ->orWhereNull('training_year_id');
            })
            ->whereDoesntHave('attempts', function ($q) use ($student) {
                if ($student) {
                    $q->where('student_id', $student->id)->where('status', 'completed');
                }
            })
            ->count();

        // Get news (public for all)
        $news = News::query()
            ->where('status', 'publish')
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->take(3)
            ->get();

        return view('student.dashboard', compact(
            'user', 
            'student', 
            'news', 
            'pendingTasks', 
            'availableQuizzes',
            'recentInstructions'
        ));
    }
}