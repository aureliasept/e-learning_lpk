<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Teacher;
use App\Models\TrainingBatch;
use App\Models\TrainingYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    /**
     * Display a listing of modules with filtering.
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

        // Query modules
        $query = Module::with('trainingBatch.trainingYear')
            ->orderBy('created_at', 'desc');

        if ($selectedBatchId) {
            $query->where('training_batch_id', $selectedBatchId);
        }

        if ($selectedClassType && $selectedClassType !== 'all') {
            $query->where('class_type', $selectedClassType);
        }

        $modules = $query->paginate(10)->withQueryString();

        // Get all courses for select dropdown
        $courses = Course::orderBy('title')->get();

        // Check which class types this instructor can access
        $canTeachReguler = $teacher ? $teacher->is_reguler : false;
        $canTeachKaryawan = $teacher ? $teacher->is_karyawan : false;

        return view('instructor.modules.index', compact(
            'trainingYears',
            'selectedYear',
            'selectedYearId',
            'batches',
            'selectedBatch',
            'selectedBatchId',
            'selectedClassType',
            'modules',
            'courses',
            'teacher',
            'canTeachReguler',
            'canTeachKaryawan'
        ));
    }

    /**
     * Show the form for creating a new module.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $trainingYears = TrainingYear::with('batches')->orderBy('name', 'desc')->get();
        $courses = Course::orderBy('title')->get();

        $selectedYearId = $request->get('year');
        $selectedBatchId = $request->get('batch');

        $canTeachReguler = $teacher ? $teacher->is_reguler : false;
        $canTeachKaryawan = $teacher ? $teacher->is_karyawan : false;

        return view('instructor.modules.create', compact(
            'trainingYears',
            'courses',
            'selectedYearId',
            'selectedBatchId',
            'teacher',
            'canTeachReguler',
            'canTeachKaryawan'
        ));
    }

    /**
     * Store a newly created module in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'training_batch_id' => 'required|exists:training_batches,id',
            'course_id' => 'nullable|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png|max:10240',
            'class_type' => 'required|in:reguler,karyawan,both',
        ]);

        // Access control
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
        $fileType = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('modules/' . $validated['training_batch_id'], 'public');
            $fileType = $request->file('file')->getClientOriginalExtension();
        }

        Module::create([
            'training_batch_id' => $validated['training_batch_id'],
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'file_type' => $fileType,
            'class_type' => $validated['class_type'],
        ]);

        return redirect()->route('instructor.modules.index', [
            'year' => TrainingBatch::find($validated['training_batch_id'])?->training_year_id,
            'batch' => $validated['training_batch_id'],
        ])->with('success', 'Materi berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified module.
     */
    public function edit(Module $module)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $trainingYears = TrainingYear::with('batches')->orderBy('name', 'desc')->get();
        $courses = Course::orderBy('title')->get();

        $canTeachReguler = $teacher ? $teacher->is_reguler : false;
        $canTeachKaryawan = $teacher ? $teacher->is_karyawan : false;

        return view('instructor.modules.edit', compact(
            'module',
            'trainingYears',
            'courses',
            'teacher',
            'canTeachReguler',
            'canTeachKaryawan'
        ));
    }

    /**
     * Update the specified module in storage.
     */
    public function update(Request $request, Module $module)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'training_batch_id' => 'required|exists:training_batches,id',
            'course_id' => 'nullable|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png|max:10240',
            'class_type' => 'required|in:reguler,karyawan,both',
        ]);

        // Access control
        if ($validated['class_type'] === 'reguler' && !$teacher?->is_reguler) {
            return back()->with('error', 'Anda tidak memiliki akses ke kelas reguler.');
        }
        if ($validated['class_type'] === 'karyawan' && !$teacher?->is_karyawan) {
            return back()->with('error', 'Anda tidak memiliki akses ke kelas karyawan.');
        }

        $filePath = $module->file_path;
        $fileType = $module->file_type;
        if ($request->hasFile('file')) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file')->store('modules/' . $validated['training_batch_id'], 'public');
            $fileType = $request->file('file')->getClientOriginalExtension();
        }

        $module->update([
            'training_batch_id' => $validated['training_batch_id'],
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'file_type' => $fileType,
            'class_type' => $validated['class_type'],
        ]);

        return redirect()->route('instructor.modules.index', [
            'year' => TrainingBatch::find($validated['training_batch_id'])?->training_year_id,
            'batch' => $validated['training_batch_id'],
        ])->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Remove the specified module from storage.
     */
    public function destroy(Module $module)
    {
        $batchId = $module->training_batch_id;
        $yearId = $module->trainingBatch?->training_year_id;

        if ($module->file_path) {
            Storage::disk('public')->delete($module->file_path);
        }

        $module->delete();

        return redirect()->route('instructor.modules.index', [
            'year' => $yearId,
            'batch' => $batchId,
        ])->with('success', 'Materi berhasil dihapus!');
    }
}