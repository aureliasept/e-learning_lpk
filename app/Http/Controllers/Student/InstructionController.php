<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseInstruction;
use App\Models\Student;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructionController extends Controller
{
    /**
     * Display timeline of instructions for student's training year.
     */
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->with('trainingYear')->first();

        if (!$student || !$student->training_year_id) {
            return view('student.instructions.index', [
                'instructions' => collect(),
                'student' => $student,
            ]);
        }

        // Filter by student's training year AND class type
        $instructions = CourseInstruction::where('training_year_id', $student->training_year_id)
            ->whereIn('class_type', [$student->type, 'all'])
            ->with(['instructor', 'submissions' => function ($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.instructions.index', compact('instructions', 'student'));
    }

    /**
     * Display the specified instruction.
     */
    public function show(CourseInstruction $instruction)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        // Ensure student can only access instructions for their training year AND class type
        if (!$student || $instruction->training_year_id !== $student->training_year_id) {
            abort(403);
        }

        // Check class type access
        if (!in_array($instruction->class_type, [$student->type, 'all'])) {
            abort(403);
        }

        $submission = $instruction->getSubmissionByStudent($student->id);

        return view('student.instructions.show', compact('instruction', 'student', 'submission'));
    }

    /**
     * Submit answer for a task.
     */
    public function submit(Request $request, CourseInstruction $instruction)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        // Ensure student can only submit to instructions for their training year AND class type
        if ($instruction->training_year_id !== $student->training_year_id) {
            abort(403);
        }

        // Check class type access
        if (!in_array($instruction->class_type, [$student->type, 'all'])) {
            abort(403);
        }

        // Check if this is a task
        if (!$instruction->is_task) {
            return back()->with('error', 'Ini bukan tugas.');
        }

        // Check deadline
        if ($instruction->isDeadlinePassed()) {
            return back()->with('error', 'Batas waktu pengumpulan sudah lewat.');
        }

        // Check if already submitted
        $existingSubmission = TaskSubmission::where('course_instruction_id', $instruction->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingSubmission) {
            return back()->with('error', 'Anda sudah mengumpulkan tugas ini.');
        }

        // Validate based on allowed response type
        $rules = [];
        if (in_array($instruction->allowed_response_type, ['file', 'both'])) {
            $rules['file'] = 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:51200';
        }
        if (in_array($instruction->allowed_response_type, ['text', 'both'])) {
            $rules['text_response'] = 'nullable|string|max:5000';
        }

        // At least one must be provided
        $validated = $request->validate($rules);

        if (empty($validated['file']) && empty($validated['text_response'])) {
            return back()->with('error', 'Harap upload file atau tulis jawaban teks.');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions/' . $instruction->id, 'public');
        }

        TaskSubmission::create([
            'course_instruction_id' => $instruction->id,
            'student_id' => $student->id,
            'file_path' => $filePath,
            'text_response' => $validated['text_response'] ?? null,
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }
}
