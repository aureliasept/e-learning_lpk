<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\CourseInstruction;
use App\Models\TaskSubmission;
use App\Models\Teacher;
use App\Models\TrainingYear;
use Carbon\Carbon;
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
        $trainingYears = TrainingYear::orderBy('name', 'desc')->get();

        // Get filters from request
        $selectedYearId = $request->get('year', $trainingYears->first()?->id);
        $selectedClassType = $request->get('class_type', 'all');

        // Get selected year
        $selectedYear = $trainingYears->firstWhere('id', $selectedYearId);

        // Query instructions
        $query = CourseInstruction::where('instructor_id', $user->id)
            ->withCount('submissions')
            ->orderBy('created_at', 'desc');

        if ($selectedYearId) {
            $query->where('training_year_id', $selectedYearId);
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

        // Get training years
        $trainingYears = TrainingYear::orderBy('name', 'desc')->get();

        // Pre-select from query params if available
        $selectedYearId = $request->get('year');

        // Check which class types this instructor can access
        $canTeachReguler = $teacher ? $teacher->is_reguler : false;
        $canTeachKaryawan = $teacher ? $teacher->is_karyawan : false;

        return view('instructor.instructions.create', compact(
            'trainingYears',
            'selectedYearId',
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
            'training_year_id' => 'required|exists:training_years,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png|max:51200',
            'is_task' => 'nullable|boolean',
            'deadline' => 'nullable|string',
            'allowed_response_type' => 'nullable|in:file,text,both',
            'class_type' => 'required|in:reguler,karyawan,all',
        ]);

        // Access control: check if instructor can create for this class type
        if ($validated['class_type'] === 'reguler' && !$teacher?->is_reguler) {
            return back()->with('error', 'Anda tidak memiliki akses ke kelas reguler.');
        }
        if ($validated['class_type'] === 'karyawan' && !$teacher?->is_karyawan) {
            return back()->with('error', 'Anda tidak memiliki akses ke kelas karyawan.');
        }
        if ($validated['class_type'] === 'all' && (!$teacher?->is_reguler || !$teacher?->is_karyawan)) {
            return back()->with('error', 'Anda tidak memiliki akses ke semua tipe kelas.');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('instructions/' . $validated['training_year_id'], 'public');
        }

        // Parse deadline from dd/mm/yyyy H:i format
        $deadline = null;
        if ($request->boolean('is_task') && !empty($validated['deadline'])) {
            $deadline = Carbon::createFromFormat('d/m/Y H:i', $validated['deadline']);
        }

        CourseInstruction::create([
            'training_year_id' => $validated['training_year_id'],
            'instructor_id' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'is_task' => $request->boolean('is_task'),
            'deadline' => $deadline,
            'allowed_response_type' => $request->boolean('is_task') ? $validated['allowed_response_type'] : null,
            'class_type' => $validated['class_type'],
        ]);

        return redirect()->route('instructor.instructions.index', [
            'year' => $validated['training_year_id'],
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

        $instruction->load(['submissions.student.user', 'trainingYear']);

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

        $trainingYears = TrainingYear::orderBy('name', 'desc')->get();

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
            'training_year_id' => 'required|exists:training_years,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png|max:51200',
            'is_task' => 'nullable|boolean',
            'deadline' => 'nullable|string',
            'allowed_response_type' => 'nullable|in:file,text,both',
            'class_type' => 'required|in:reguler,karyawan,all',
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
            $filePath = $request->file('file')->store('instructions/' . $validated['training_year_id'], 'public');
        }

        // Parse deadline from dd/mm/yyyy H:i format
        $deadline = null;
        if ($request->boolean('is_task') && !empty($validated['deadline'])) {
            $deadline = Carbon::createFromFormat('d/m/Y H:i', $validated['deadline']);
        }

        $instruction->update([
            'training_year_id' => $validated['training_year_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'is_task' => $request->boolean('is_task'),
            'deadline' => $deadline,
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

        $yearId = $instruction->training_year_id;

        if ($instruction->file_path) {
            Storage::disk('public')->delete($instruction->file_path);
        }

        $instruction->delete();

        return redirect()->route('instructor.instructions.index', [
            'year' => $yearId,
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
