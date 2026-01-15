<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\CourseInstruction;
use App\Models\TaskSubmission;
use App\Models\Teacher;
use App\Models\TrainingBatch;
use App\Models\TrainingYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InstructionController extends Controller
{
    /**
     * Display a listing of instructions with filtering.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        // Get all training years
        $trainingYears = TrainingYear::with('batches')->orderBy('name', 'desc')->get();

        // Get filters from request
        $selectedYearId = $request->get('year', $trainingYears->first()?->id);
        $selectedBatchId = $request->get('batch');
        $selectedClassType = $request->get('class_type', 'all');

        // Get selected year and its batches
        $selectedYear = $trainingYears->firstWhere('id', $selectedYearId);
        $batches = $selectedYear ? $selectedYear->batches : collect();

        // Auto-select first batch if none selected
        if (!$selectedBatchId && $batches->count() > 0) {
            $selectedBatchId = $batches->first()->id;
        }
        $selectedBatch = $batches->firstWhere('id', $selectedBatchId);

        // Query instructions
        $query = CourseInstruction::where('instructor_id', $user->id)
            ->withCount('submissions')
            ->orderBy('created_at', 'desc');

        if ($selectedBatchId) {
            $query->where('training_batch_id', $selectedBatchId);
        }

        if ($selectedClassType && $selectedClassType !== 'all') {
            $query->where('class_type', $selectedClassType);
        }

        $instructions = $query->paginate(10)->withQueryString();

        // Check which class types this instructor can access
        $canTeachReguler = $teacher ? $teacher->is_reguler : false;
        $canTeachKaryawan = $teacher ? $teacher->is_karyawan : false;

        return view('instructor.instructions.index', compact(
            'trainingYears',
            'selectedYear',
            'selectedYearId',
            'batches',
            'selectedBatch',
            'selectedBatchId',
            'selectedClassType',
            'instructions',
            'teacher',
            'canTeachReguler',
            'canTeachKaryawan'
        ));
    }

    /**
     * Show form for creating new instruction.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        // Get training years and batches
        $trainingYears = TrainingYear::with('batches')->orderBy('name', 'desc')->get();

        // Pre-select from query params if available
        $selectedYearId = $request->get('year');
        $selectedBatchId = $request->get('batch');

        // Check which class types this instructor can access
        $canTeachReguler = $teacher ? $teacher->is_reguler : false;
        $canTeachKaryawan = $teacher ? $teacher->is_karyawan : false;

        return view('instructor.instructions.create', compact(
            'trainingYears',
            'selectedYearId',
            'selectedBatchId',
            'teacher',
            'canTeachReguler',
            'canTeachKaryawan'
        ));
    }

    /**
     * Store a newly created instruction.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'training_batch_id' => 'required|exists:training_batches,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png|max:10240',
            'is_task' => 'nullable|boolean',
            'deadline' => 'nullable|date|after:now',
            'allowed_response_type' => 'nullable|in:file,text,both',
            'class_type' => 'required|in:reguler,karyawan,both',
        ]);

        // Access control: check if instructor can create for this class type
        if ($validated['class_type'] === 'reguler' && !$teacher?->is_reguler) {
            return back()->with('error', 'Anda tidak memiliki akses ke kelas reguler.');
        }
        if ($validated['class_type'] === 'karyawan' && !$teacher?->is_karyawan) {
            return back()->with('error', 'Anda tidak memiliki akses ke kelas karyawan.');
        }
        if ($validated['class_type'] === 'both' && (!$teacher?->is_reguler || !$teacher?->is_karyawan)) {
            return back()->with('error', 'Anda tidak memiliki akses ke kedua tipe kelas.');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('instructions/' . $validated['training_batch_id'], 'public');
        }

        CourseInstruction::create([
            'training_batch_id' => $validated['training_batch_id'],
            'instructor_id' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'is_task' => $request->boolean('is_task'),
            'deadline' => $request->boolean('is_task') ? $validated['deadline'] : null,
            'allowed_response_type' => $request->boolean('is_task') ? $validated['allowed_response_type'] : null,
            'class_type' => $validated['class_type'],
        ]);

        return redirect()->route('instructor.instructions.index', [
            'year' => TrainingBatch::find($validated['training_batch_id'])?->training_year_id,
            'batch' => $validated['training_batch_id'],
        ])->with('success', 'Instruksi berhasil dibuat!');
    }

    /**
     * Display the specified instruction with submissions.
     */
    public function show(CourseInstruction $instruction)
    {
        // Authorization: must be owner
        if ($instruction->instructor_id !== Auth::id()) {
            abort(403);
        }

        $instruction->load(['submissions.student.user', 'trainingBatch.trainingYear']);

        return view('instructor.instructions.show', compact('instruction'));
    }

    /**
     * Show edit form for instruction.
     */
    public function edit(CourseInstruction $instruction)
    {
        if ($instruction->instructor_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $trainingYears = TrainingYear::with('batches')->orderBy('name', 'desc')->get();

        $canTeachReguler = $teacher ? $teacher->is_reguler : false;
        $canTeachKaryawan = $teacher ? $teacher->is_karyawan : false;

        return view('instructor.instructions.edit', compact(
            'instruction',
            'trainingYears',
            'teacher',
            'canTeachReguler',
            'canTeachKaryawan'
        ));
    }

    /**
     * Update the specified instruction.
     */
    public function update(Request $request, CourseInstruction $instruction)
    {
        if ($instruction->instructor_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'training_batch_id' => 'required|exists:training_batches,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png|max:10240',
            'is_task' => 'nullable|boolean',
            'deadline' => 'nullable|date',
            'allowed_response_type' => 'nullable|in:file,text,both',
            'class_type' => 'required|in:reguler,karyawan,both',
        ]);

        // Access control
        if ($validated['class_type'] === 'reguler' && !$teacher?->is_reguler) {
            return back()->with('error', 'Anda tidak memiliki akses ke kelas reguler.');
        }
        if ($validated['class_type'] === 'karyawan' && !$teacher?->is_karyawan) {
            return back()->with('error', 'Anda tidak memiliki akses ke kelas karyawan.');
        }

        $filePath = $instruction->file_path;
        if ($request->hasFile('file')) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file')->store('instructions/' . $validated['training_batch_id'], 'public');
        }

        $instruction->update([
            'training_batch_id' => $validated['training_batch_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'is_task' => $request->boolean('is_task'),
            'deadline' => $request->boolean('is_task') ? $validated['deadline'] : null,
            'allowed_response_type' => $request->boolean('is_task') ? $validated['allowed_response_type'] : null,
            'class_type' => $validated['class_type'],
        ]);

        return redirect()->route('instructor.instructions.show', $instruction)
            ->with('success', 'Instruksi berhasil diperbarui!');
    }

    /**
     * Remove the specified instruction.
     */
    public function destroy(CourseInstruction $instruction)
    {
        if ($instruction->instructor_id !== Auth::id()) {
            abort(403);
        }

        $batchId = $instruction->training_batch_id;
        $yearId = $instruction->trainingBatch?->training_year_id;

        if ($instruction->file_path) {
            Storage::disk('public')->delete($instruction->file_path);
        }

        $instruction->delete();

        return redirect()->route('instructor.instructions.index', [
            'year' => $yearId,
            'batch' => $batchId,
        ])->with('success', 'Instruksi berhasil dihapus!');
    }

    /**
     * Grade a student submission.
     */
    public function grade(Request $request, TaskSubmission $submission)
    {
        $instruction = $submission->courseInstruction;
        
        if ($instruction->instructor_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'grade' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $submission->update($validated);

        return back()->with('success', 'Nilai berhasil disimpan!');
    }
}
