<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ChapterController extends Controller
{
    public function index()
    {
        return redirect()->route('instructor.dashboard');
    }

    public function create(Request $request)
    {
        $courseId = $request->query('course_id');

        $courseQuery = Course::query();
        if (Schema::hasColumn('courses', 'teacher_id')) {
            $courseQuery->where('teacher_id', Auth::id());
        } elseif (Schema::hasColumn('courses', 'instruktur_id')) {
            $courseQuery->where('instruktur_id', Auth::id());
        }

        $course = null;
        if ($courseId) {
            $course = $courseQuery->findOrFail($courseId);
        }

        $modulesQuery = Module::query();
        if ($course && Schema::hasColumn('modules', 'course_id')) {
            $modulesQuery->where('course_id', $course->id);
        }

        $modules = $modulesQuery->orderBy('id')->get();

        return view('instructor.chapters.create', [
            'course' => $course,
            'modules' => $modules,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['nullable', 'integer'],
            'module_id' => ['required', 'exists:modules,id'],
            'title' => ['required', 'string', 'max:255'],
            'instruction' => ['required', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:51200'],
        ]);

        $module = Module::findOrFail($validated['module_id']);

        if (Schema::hasTable('courses')) {
            $courseQuery = Course::query();
            if (Schema::hasColumn('courses', 'teacher_id')) {
                $courseQuery->where('teacher_id', Auth::id());
            } elseif (Schema::hasColumn('courses', 'instruktur_id')) {
                $courseQuery->where('instruktur_id', Auth::id());
            }

            $courseId = Schema::hasColumn('modules', 'course_id') ? ($module->course_id ?? null) : null;
            if ($courseId) {
                $courseQuery->where('id', $courseId)->firstOrFail();
            }
        }

        $data = [
            'module_id' => $module->id,
            'title' => $validated['title'],
        ];

        if (Schema::hasColumn('chapters', 'instruction')) {
            $data['instruction'] = $validated['instruction'];
        } else {
            $data['content'] = $validated['instruction'];
        }

        // Handle file upload
        if ($request->hasFile('file') && Schema::hasColumn('chapters', 'file_path')) {
            $data['file_path'] = $request->file('file')->store('chapters', 'public');
        }

        Chapter::create($data);

        $redirectCourseId = $validated['course_id'] ?? (Schema::hasColumn('modules', 'course_id') ? ($module->course_id ?? null) : null);
        if ($redirectCourseId) {
            return redirect()->route('instructor.courses.show', $redirectCourseId)->with('success', 'Pertemuan berhasil ditambahkan.');
        }

        return redirect()->route('instructor.dashboard')->with('success', 'Pertemuan berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $chapter = Chapter::with('module')->findOrFail($id);

        $course = null;
        if (Schema::hasTable('courses') && Schema::hasColumn('modules', 'course_id')) {
            $courseId = $chapter->module?->course_id;
            if ($courseId) {
                $courseQuery = Course::query();
                if (Schema::hasColumn('courses', 'teacher_id')) {
                    $courseQuery->where('teacher_id', Auth::id());
                } elseif (Schema::hasColumn('courses', 'instruktur_id')) {
                    $courseQuery->where('instruktur_id', Auth::id());
                }

                $course = $courseQuery->findOrFail($courseId);
            }
        }

        return view('instructor.chapters.create', [
            'course' => $course,
            'modules' => Module::query()
                ->when($course && Schema::hasColumn('modules', 'course_id'), fn ($q) => $q->where('course_id', $course->id))
                ->orderBy('id')
                ->get(),
            'chapter' => $chapter,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $chapter = Chapter::with('module')->findOrFail($id);

        $validated = $request->validate([
            'course_id' => ['nullable', 'integer'],
            'module_id' => ['required', 'exists:modules,id'],
            'title' => ['required', 'string', 'max:255'],
            'instruction' => ['required', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:51200'],
        ]);

        $module = Module::findOrFail($validated['module_id']);

        $data = [
            'module_id' => $module->id,
            'title' => $validated['title'],
        ];

        if (Schema::hasColumn('chapters', 'instruction')) {
            $data['instruction'] = $validated['instruction'];
        } else {
            $data['content'] = $validated['instruction'];
        }

        // Handle file upload
        if ($request->hasFile('file') && Schema::hasColumn('chapters', 'file_path')) {
            // Delete old file if exists
            if ($chapter->file_path && Storage::disk('public')->exists($chapter->file_path)) {
                Storage::disk('public')->delete($chapter->file_path);
            }
            $data['file_path'] = $request->file('file')->store('chapters', 'public');
        }

        $chapter->update($data);

        $redirectCourseId = $validated['course_id'] ?? (Schema::hasColumn('modules', 'course_id') ? ($module->course_id ?? null) : null);
        if ($redirectCourseId) {
            return redirect()->route('instructor.courses.show', $redirectCourseId)->with('success', 'Pertemuan berhasil diperbarui.');
        }

        return redirect()->route('instructor.dashboard')->with('success', 'Pertemuan berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $chapter = Chapter::with('module')->findOrFail($id);

        $courseId = null;
        if (Schema::hasColumn('modules', 'course_id')) {
            $courseId = $chapter->module?->course_id;
        }

        $chapter->delete();

        if ($courseId) {
            return redirect()->route('instructor.courses.show', $courseId)->with('success', 'Pertemuan berhasil dihapus.');
        }

        return redirect()->route('instructor.dashboard')->with('success', 'Pertemuan berhasil dihapus.');
    }
}
