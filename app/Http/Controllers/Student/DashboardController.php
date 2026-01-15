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
        $student = Student::where('user_id', $user->id)->with('trainingBatch')->first();
        
        $batchId = $student?->training_batch_id;

        // Get pending tasks (tasks not yet submitted) - filtered by batch
        $pendingTasks = 0;
        $recentInstructions = collect();
        if ($batchId) {
            $studentId = $student->id;
            $recentInstructions = CourseInstruction::where('training_batch_id', $batchId)
                ->with(['instructor', 'submissions' => function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                }])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $pendingTasks = CourseInstruction::where('training_batch_id', $batchId)
                ->where('is_task', true)
                ->where('deadline', '>', now())
                ->whereDoesntHave('submissions', function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                })
                ->count();
        }

        // Get available quizzes - filtered by batch
        $availableQuizzes = Quiz::where('is_active', true)
            ->where(function ($query) use ($batchId) {
                $query->where('training_batch_id', $batchId)
                    ->orWhereNull('training_batch_id');
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