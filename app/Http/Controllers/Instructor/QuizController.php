<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Imports\QuestionImport;
use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\TrainingBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuizController extends Controller
{
    /**
     * Display a listing of instructor's quizzes.
     */
    public function index()
    {
        $quizzes = Quiz::where('instructor_id', Auth::id())
            ->with(['trainingBatch', 'questions'])
            ->latest()
            ->paginate(10);

        return view('instructor.quizzes.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create()
    {
        $batches = TrainingBatch::with('trainingYear')
            ->orderBy('id', 'desc')
            ->get();

        return view('instructor.quizzes.create', compact('batches'));
    }

    /**
     * Store a newly created quiz.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1|max:300',
            'training_batch_id' => 'nullable|exists:training_batches,id',
            'passing_score' => 'required|integer|min:0|max:100',
            'use_access_code' => 'nullable|boolean',
            'show_answers_after' => 'nullable|boolean',
            'shuffle_questions' => 'nullable|boolean',
            'shuffle_options' => 'nullable|boolean',
            'questions' => 'nullable|array',
            'questions.*.question_text' => 'required_with:questions|string',
            'questions.*.explanation' => 'nullable|string',
            'questions.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'questions.*.audio' => 'nullable|mimes:mp3,wav,ogg|max:10240',
            'questions.*.options' => 'required_with:questions|array|min:2',
            'questions.*.options.*.text' => 'required_with:questions.*.options|string',
            'questions.*.correct_option' => 'required_with:questions|integer|min:0',
            'import_file' => 'nullable|mimes:xlsx,xls|max:5120',
        ]);

        // Require either manual questions or import file
        if (empty($validated['questions']) && !$request->hasFile('import_file')) {
            return back()->withErrors(['questions' => 'Tambahkan minimal 1 soal atau upload file Excel untuk import.'])->withInput();
        }

        $quiz = null;

        DB::transaction(function () use ($validated, $request, &$quiz) {
            $quiz = Quiz::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'duration_minutes' => $validated['duration_minutes'],
                'training_batch_id' => $validated['training_batch_id'],
                'instructor_id' => Auth::id(),
                'passing_score' => $validated['passing_score'],
                'is_active' => true,
                'access_code' => $request->has('use_access_code') ? Quiz::generateAccessCode() : null,
                'show_answers_after' => $request->boolean('show_answers_after'),
                'shuffle_questions' => $request->boolean('shuffle_questions'),
                'shuffle_options' => $request->boolean('shuffle_options'),
            ]);

            // Create manual questions if any
            if (!empty($validated['questions'])) {
                foreach ($validated['questions'] as $index => $questionData) {
                    $imageUrl = null;
                    $audioUrl = null;

                    // Handle image upload
                    if ($request->hasFile("questions.{$index}.image")) {
                        $imagePath = $request->file("questions.{$index}.image")->store('quizzes/images', 'public');
                        $imageUrl = $imagePath;
                    }

                    // Handle audio upload
                    if ($request->hasFile("questions.{$index}.audio")) {
                        $audioPath = $request->file("questions.{$index}.audio")->store('quizzes/audio', 'public');
                        $audioUrl = $audioPath;
                    }

                    $question = Question::create([
                        'quiz_id' => $quiz->id,
                        'question_text' => $questionData['question_text'],
                        'explanation' => $questionData['explanation'] ?? null,
                        'image_url' => $imageUrl,
                        'audio_url' => $audioUrl,
                        'order' => $index + 1,
                    ]);

                    foreach ($questionData['options'] as $optIndex => $optionData) {
                        Option::create([
                            'question_id' => $question->id,
                            'option_text' => $optionData['text'],
                            'is_correct' => $optIndex == $questionData['correct_option'],
                        ]);
                    }
                }
            }
        });

        // Import from Excel if file provided
        if ($request->hasFile('import_file') && $quiz) {
            $startOrder = $quiz->questions()->max('order') ?? 0;
            Excel::import(new QuestionImport($quiz->id, $startOrder), $request->file('import_file'));
        }

        return redirect()->route('instructor.quizzes.index')
            ->with('success', 'Quiz berhasil dibuat!');
    }

    /**
     * Display the specified quiz with analytics.
     */
    public function show(Quiz $quiz)
    {
        $this->authorize('view', $quiz);
        
        $quiz->load(['questions.options', 'trainingBatch', 'attempts.student.user']);

        // Calculate analytics
        $analytics = $this->calculateAnalytics($quiz);

        return view('instructor.quizzes.show', compact('quiz', 'analytics'));
    }

    /**
     * Calculate question analytics for a quiz.
     */
    protected function calculateAnalytics(Quiz $quiz): array
    {
        $completedAttempts = $quiz->attempts()->where('status', 'completed')->get();
        $totalAttempts = $completedAttempts->count();

        if ($totalAttempts === 0) {
            return [
                'total_attempts' => 0,
                'average_score' => 0,
                'pass_rate' => 0,
                'questions' => [],
            ];
        }

        $averageScore = $completedAttempts->avg('score');
        $passedCount = $completedAttempts->where('score', '>=', $quiz->passing_score)->count();
        $passRate = ($passedCount / $totalAttempts) * 100;

        // Per-question analytics
        $questionStats = [];
        foreach ($quiz->questions as $question) {
            $correctOption = $question->options->where('is_correct', true)->first();
            $correctCount = 0;

            foreach ($completedAttempts as $attempt) {
                $answers = $attempt->answers ?? [];
                $studentAnswer = $answers[$question->id] ?? null;
                
                if ($correctOption && $studentAnswer == $correctOption->id) {
                    $correctCount++;
                }
            }

            $correctPercentage = ($correctCount / $totalAttempts) * 100;
            
            $questionStats[] = [
                'id' => $question->id,
                'order' => $question->order,
                'question_text' => $question->question_text,
                'correct_count' => $correctCount,
                'incorrect_count' => $totalAttempts - $correctCount,
                'correct_percentage' => round($correctPercentage, 1),
                'difficulty' => $this->getDifficultyLabel($correctPercentage),
            ];
        }

        // Sort by correct percentage (hardest first)
        usort($questionStats, fn($a, $b) => $a['correct_percentage'] <=> $b['correct_percentage']);

        return [
            'total_attempts' => $totalAttempts,
            'average_score' => round($averageScore, 1),
            'pass_rate' => round($passRate, 1),
            'questions' => $questionStats,
        ];
    }

    /**
     * Get difficulty label based on correct percentage.
     */
    protected function getDifficultyLabel(float $correctPercentage): string
    {
        if ($correctPercentage < 30) return 'Sangat Sulit';
        if ($correctPercentage < 50) return 'Sulit';
        if ($correctPercentage < 70) return 'Sedang';
        if ($correctPercentage < 90) return 'Mudah';
        return 'Sangat Mudah';
    }

    /**
     * Show the form for editing the specified quiz.
     */
    public function edit(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        
        $quiz->load('questions.options');
        $batches = TrainingBatch::with('trainingYear')
            ->orderBy('id', 'desc')
            ->get();

        return view('instructor.quizzes.edit', compact('quiz', 'batches'));
    }

    /**
     * Update the specified quiz.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1|max:300',
            'training_batch_id' => 'nullable|exists:training_batches,id',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_active' => 'boolean',
            'use_access_code' => 'nullable|boolean',
            'access_code' => 'nullable|string|max:6',
            'show_answers_after' => 'nullable|boolean',
            'shuffle_questions' => 'nullable|boolean',
            'shuffle_options' => 'nullable|boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.explanation' => 'nullable|string',
            'questions.*.existing_image' => 'nullable|string',
            'questions.*.existing_audio' => 'nullable|string',
            'questions.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'questions.*.audio' => 'nullable|mimes:mp3,wav,ogg|max:10240',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.correct_option' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated, $quiz, $request) {
            // Handle access code
            $accessCode = $quiz->access_code;
            if ($request->has('use_access_code')) {
                if (empty($accessCode)) {
                    $accessCode = Quiz::generateAccessCode();
                }
            } else {
                $accessCode = null;
            }

            $quiz->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'duration_minutes' => $validated['duration_minutes'],
                'training_batch_id' => $validated['training_batch_id'],
                'passing_score' => $validated['passing_score'],
                'is_active' => $validated['is_active'] ?? true,
                'access_code' => $accessCode,
                'show_answers_after' => $request->boolean('show_answers_after'),
                'shuffle_questions' => $request->boolean('shuffle_questions'),
                'shuffle_options' => $request->boolean('shuffle_options'),
            ]);

            // Collect files that should be preserved (from existing_image and existing_audio fields)
            $filesToKeep = [];
            foreach ($validated['questions'] as $questionData) {
                if (!empty($questionData['existing_image'])) {
                    $filesToKeep[] = $questionData['existing_image'];
                }
                if (!empty($questionData['existing_audio'])) {
                    $filesToKeep[] = $questionData['existing_audio'];
                }
            }

            // Delete old files ONLY if they are not in the keep list
            foreach ($quiz->questions as $oldQuestion) {
                if ($oldQuestion->image_url && !in_array($oldQuestion->image_url, $filesToKeep)) {
                    Storage::disk('public')->delete($oldQuestion->image_url);
                }
                if ($oldQuestion->audio_url && !in_array($oldQuestion->audio_url, $filesToKeep)) {
                    Storage::disk('public')->delete($oldQuestion->audio_url);
                }
            }
            
            // Delete old questions (options will be cascade deleted)
            $quiz->questions()->delete();

            // Create new questions
            foreach ($validated['questions'] as $index => $questionData) {
                $imageUrl = $questionData['existing_image'] ?? null;
                $audioUrl = $questionData['existing_audio'] ?? null;

                // Handle new image upload (overrides existing if provided)
                if ($request->hasFile("questions.{$index}.image")) {
                    // Delete old existing image if we're uploading a new one
                    if ($imageUrl) {
                        Storage::disk('public')->delete($imageUrl);
                    }
                    $imagePath = $request->file("questions.{$index}.image")->store('quizzes/images', 'public');
                    $imageUrl = $imagePath;
                }

                // Handle new audio upload (overrides existing if provided)
                if ($request->hasFile("questions.{$index}.audio")) {
                    // Delete old existing audio if we're uploading a new one
                    if ($audioUrl) {
                        Storage::disk('public')->delete($audioUrl);
                    }
                    $audioPath = $request->file("questions.{$index}.audio")->store('quizzes/audio', 'public');
                    $audioUrl = $audioPath;
                }

                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $questionData['question_text'],
                    'explanation' => $questionData['explanation'] ?? null,
                    'image_url' => $imageUrl,
                    'audio_url' => $audioUrl,
                    'order' => $index + 1,
                ]);

                foreach ($questionData['options'] as $optIndex => $optionData) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $optionData['text'],
                        'is_correct' => $optIndex == $questionData['correct_option'],
                    ]);
                }
            }
        });

        return redirect()->route('instructor.quizzes.index')
            ->with('success', 'Quiz berhasil diperbarui!');
    }

    /**
     * Remove the specified quiz.
     */
    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);

        // Delete associated files
        foreach ($quiz->questions as $question) {
            if ($question->image_url) {
                Storage::disk('public')->delete($question->image_url);
            }
            if ($question->audio_url) {
                Storage::disk('public')->delete($question->audio_url);
            }
        }

        $quiz->delete();

        return redirect()->route('instructor.quizzes.index')
            ->with('success', 'Quiz berhasil dihapus!');
    }

    /**
     * Toggle quiz active status.
     */
    public function toggleActive(Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        $quiz->update(['is_active' => !$quiz->is_active]);

        return back()->with('success', 'Status quiz berhasil diubah!');
    }

    /**
     * Regenerate access code for quiz.
     */
    public function regenerateCode(Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        $quiz->update(['access_code' => Quiz::generateAccessCode()]);

        return back()->with('success', 'Kode akses berhasil di-generate ulang!');
    }

    /**
     * Download Excel template for question import.
     */
    public function downloadTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="template_soal_quiz.xlsx"',
        ];

        return response()->streamDownload(function () {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $sheet->setCellValue('A1', 'pertanyaan');
            $sheet->setCellValue('B1', 'pilihan_a');
            $sheet->setCellValue('C1', 'pilihan_b');
            $sheet->setCellValue('D1', 'pilihan_c');
            $sheet->setCellValue('E1', 'pilihan_d');
            $sheet->setCellValue('F1', 'pilihan_e');
            $sheet->setCellValue('G1', 'jawaban_benar');
            $sheet->setCellValue('H1', 'pembahasan');

            // Style headers
            $headerStyle = [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D4AF37'],
                ],
            ];
            $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

            // Example row
            $sheet->setCellValue('A2', 'Apa arti dari 「おはようございます」?');
            $sheet->setCellValue('B2', 'Selamat pagi');
            $sheet->setCellValue('C2', 'Selamat siang');
            $sheet->setCellValue('D2', 'Selamat malam');
            $sheet->setCellValue('E2', 'Selamat tidur');
            $sheet->setCellValue('F2', '');
            $sheet->setCellValue('G2', 'A');
            $sheet->setCellValue('H2', 'おはようございます (ohayou gozaimasu) adalah salam untuk pagi hari.');

            // Another example
            $sheet->setCellValue('A3', 'Hiragana dari "sakura" adalah...');
            $sheet->setCellValue('B3', 'さくら');
            $sheet->setCellValue('C3', 'サクラ');
            $sheet->setCellValue('D3', '桜');
            $sheet->setCellValue('E3', 'さくろ');
            $sheet->setCellValue('F3', '');
            $sheet->setCellValue('G3', 'A');
            $sheet->setCellValue('H3', 'さくら adalah penulisan hiragana yang benar untuk sakura (bunga sakura).');

            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(40);
            $sheet->getColumnDimension('B')->setWidth(20);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(15);
            $sheet->getColumnDimension('H')->setWidth(50);

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'template_soal_quiz.xlsx', $headers);
    }

    /**
     * Import questions from Excel file.
     */
    public function importQuestions(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120',
        ]);

        $startOrder = $quiz->questions()->max('order') ?? 0;

        Excel::import(new QuestionImport($quiz->id, $startOrder), $request->file('file'));

        return back()->with('success', 'Soal berhasil diimport dari Excel!');
    }
}
