<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    /**
     * Display a listing of modules for the student's training year and class type.
     */
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student || !$student->training_year_id) {
            return view('student.modules.index', [
                'modules' => collect(),
                'student' => $student,
            ]);
        }

        // Get modules matching student's training year AND class type
        // class_type should be student's type OR 'both'
        $modules = Module::where('training_year_id', $student->training_year_id)
            ->whereIn('class_type', [$student->type, 'both'])
            ->with('trainingYear')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('student.modules.index', compact('modules', 'student'));
    }

    /**
     * Display the specified module.
     */
    public function show(Module $module)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        // Ensure student can only access modules for their training year AND class type
        if (!$student || $module->training_year_id !== $student->training_year_id) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        // Check class type access
        if (!in_array($module->class_type, [$student->type, 'both'])) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        return view('student.modules.show', compact('module', 'student'));
    }

    /**
     * Download file materi dengan nama file asli.
     */
    public function download(Module $module)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        // Cek akses student
        if (!$student || $module->training_year_id !== $student->training_year_id) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        if (!in_array($module->class_type, [$student->type, 'both'])) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        // Cek apakah file ada
        if (!$module->file_path || !Storage::disk('public')->exists($module->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Gunakan nama file asli jika tersedia, jika tidak gunakan judul modul
        $filename = $module->original_filename ?? ($module->title . '.' . $module->file_type);

        return Storage::disk('public')->download($module->file_path, $filename);
    }
}

