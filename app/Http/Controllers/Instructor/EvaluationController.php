<?php

namespace App\Http\Controllers\Instructor; // FIX: Instruktur -> Instructor

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\StudentGrade;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvaluationController extends Controller
{
    public function index(): View
    {
        $quizzes = Quiz::latest()->get();
        // FIX View Path: instruktur -> instructor, evaluations -> quizzes
        return view('instructor.quizzes.index', compact('quizzes'));
    }

    public function create(): View
    {
        return view('instructor.quizzes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'integer', 'min:1'],
            'passing_grade' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $quiz = Quiz::create([
            'title' => $validated['title'],
            'duration' => (int) $validated['duration'],
            'passing_grade' => (int) ($validated['passing_grade'] ?? 0),
        ]);

        // FIX Route Name: instruktur.evaluations -> instructor.quizzes
        return redirect()->route('instructor.quizzes.show', $quiz->id)
                         ->with('success', 'Quiz berhasil dibuat.');
    }

    public function show(Quiz $quiz): View // Binding model diganti $quiz agar rapi (opsional)
    {
        // $evaluation diubah jadi $quiz agar konsisten dengan Resource Controller
        $quiz->load('questions');

        return view('instructor.quizzes.show', [
            'quiz' => $quiz,
        ]);
    }

    // Custom method untuk melihat nilai
    public function showGrades(Quiz $quiz): View
    {
        $quiz->loadMissing('questions');

        $grades = StudentGrade::query()
            ->with('user')
            ->where('quiz_id', $quiz->id)
            ->orderByDesc('score')
            ->get();

        $totalParticipants = $grades->count();
        $averageScore = $totalParticipants > 0 ? round($grades->avg('score'), 2) : 0;

        return view('instructor.quizzes.grades', [
            'quiz' => $quiz,
            'grades' => $grades,
            'totalParticipants' => $totalParticipants,
            'averageScore' => $averageScore,
        ]);
    }

    // Custom method simpan soal
    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_text' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_option' => ['required', 'in:a,b,c,d'],
        ]);

        Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => $validated['question_text'],
            'option_a' => $validated['option_a'],
            'option_b' => $validated['option_b'],
            'option_c' => $validated['option_c'],
            'option_d' => $validated['option_d'],
            'correct_option' => $validated['correct_option'],
        ]);

        // FIX Route Name
        return redirect()->route('instructor.quizzes.show', $quiz->id)
                         ->with('success', 'Soal berhasil ditambahkan.');
    }
}