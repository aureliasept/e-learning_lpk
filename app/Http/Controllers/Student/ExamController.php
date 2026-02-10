<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    /**
     * Display a listing of available quizzes for the student.
     */
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return view('student.exam.index', [
                'quizzes' => collect(),
                'student' => null,
            ]);
        }

        // Get quizzes for student's training year or all training years
        $quizzes = Quiz::where('is_active', true)
            ->where(function ($query) use ($student) {
                $query->where('training_year_id', $student->training_year_id)
                    ->orWhereNull('training_year_id');
            })
            ->with(['questions', 'attempts' => function ($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
            ->latest()
            ->get();

        return view('student.exam.index', compact('quizzes', 'student'));
    }

    /**
     * Show the access code verification page.
     */
    public function showVerify(Quiz $quiz)
    {
        if (!$quiz->requiresAccessCode()) {
            return redirect()->route('student.exam.start', $quiz);
        }

        return view('student.exam.verify-code', compact('quiz'));
    }

    /**
     * Verify the access code.
     */
    public function verifyCode(Request $request, Quiz $quiz)
    {
        $request->validate([
            'access_code' => 'required|string|size:6',
        ]);

        if (strtoupper($request->access_code) !== $quiz->access_code) {
            return back()->withErrors(['access_code' => 'Kode akses tidak valid.']);
        }

        // Store verification in session
        session()->put("quiz_verified_{$quiz->id}", true);

        return redirect()->route('student.exam.start', $quiz);
    }

    /**
     * Start or resume a quiz attempt.
     */
    public function start(Quiz $quiz)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        // Check if quiz is active
        if (!$quiz->is_active) {
            return redirect()->route('student.exam.index')
                ->with('error', 'Quiz ini tidak aktif.');
        }

        // Check access code verification
        if ($quiz->requiresAccessCode() && !session()->get("quiz_verified_{$quiz->id}")) {
            return redirect()->route('student.exam.verify', $quiz);
        }

        // Check for existing in-progress attempt
        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->first();

        // Check for completed attempt
        $completedAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->first();

        if ($completedAttempt) {
            return redirect()->route('student.exam.result', $completedAttempt)
                ->with('info', 'Anda sudah menyelesaikan quiz ini.');
        }

        // Create new attempt if none exists
        if (!$attempt) {
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
                'answers' => [],
                'marked_for_review' => [],
                'started_at' => now(),
                'status' => 'in_progress',
            ]);
        }

        // Load quiz with questions and options
        $quiz->load(['questions' => function ($q) use ($quiz) {
            if ($quiz->shuffle_questions) {
                $q->inRandomOrder();
            } else {
                $q->orderBy('order');
            }
            $q->with(['options' => function ($optQ) use ($quiz) {
                if ($quiz->shuffle_options) {
                    $optQ->inRandomOrder();
                }
            }]);
        }]);

        // Calculate remaining time
        $elapsedSeconds = now()->diffInSeconds($attempt->started_at);
        $totalSeconds = $quiz->duration_minutes * 60;
        $remainingSeconds = max(0, $totalSeconds - $elapsedSeconds);

        // Auto-submit if time is up
        if ($remainingSeconds <= 0) {
            return $this->autoSubmit($attempt);
        }

        // Prepare questions JSON for JavaScript (avoid Blade parsing issues)
        $questionsJson = $quiz->questions->map(function ($q) {
            return [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'image_url' => $q->image_url,
                'audio_url' => $q->audio_url,
                'options' => $q->options->map(function ($o) {
                    return [
                        'id' => $o->id,
                        'option_text' => $o->option_text,
                    ];
                })->values(),
            ];
        })->values();

        return view('student.exam.show', compact('quiz', 'attempt', 'remainingSeconds', 'questionsJson'));
    }

    /**
     * Save answers periodically (AJAX).
     */
    public function saveAnswers(Request $request, QuizAttempt $attempt)
    {
        if ($attempt->status === 'completed') {
            return response()->json(['error' => 'Quiz sudah selesai.'], 400);
        }

        $attempt->update([
            'answers' => $request->input('answers', []),
            'marked_for_review' => $request->input('marked_for_review', []),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Submit the quiz and calculate score.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $answers = $request->input('answers', []);
        
        return $this->processSubmission($attempt, $answers);
    }

    /**
     * Auto-submit when time runs out.
     */
    protected function autoSubmit(QuizAttempt $attempt)
    {
        $answers = $attempt->answers ?? [];
        return $this->processSubmission($attempt, $answers);
    }

    /**
     * Process submission and calculate score.
     */
    protected function processSubmission(QuizAttempt $attempt, array $answers)
    {
        $quiz = $attempt->quiz;
        $quiz->load('questions.options');

        $totalCorrect = 0;
        $totalQuestions = $quiz->questions->count();

        foreach ($quiz->questions as $question) {
            $correctOption = $question->options->where('is_correct', true)->first();
            $studentAnswer = $answers[$question->id] ?? null;

            if ($correctOption && $studentAnswer == $correctOption->id) {
                $totalCorrect++;
            }
        }

        $score = $totalQuestions > 0 ? round(($totalCorrect / $totalQuestions) * 100) : 0;

        $attempt->update([
            'answers' => $answers,
            'score' => $score,
            'total_correct' => $totalCorrect,
            'finished_at' => now(),
            'status' => 'completed',
        ]);

        // Clear session verification
        session()->forget("quiz_verified_{$quiz->id}");

        return redirect()->route('student.exam.result', $attempt);
    }

    /**
     * Display the result of a quiz attempt.
     */
    public function result(QuizAttempt $attempt)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        // Ensure student can only view their own results
        if ($attempt->student_id !== $student->id) {
            abort(403);
        }

        $attempt->load(['quiz.questions.options', 'student.user']);

        return view('student.exam.result', compact('attempt'));
    }
}
