<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    // ==========================================
    //  KELOLA MODUL (COURSES)
    // ==========================================
    public function index()
    {
        $courses = Course::latest()->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required', 'level' => 'required']);
        Course::create($request->all());
        return redirect()->route('admin.courses.index')->with('success', 'Modul berhasil dibuat.');
    }

    public function edit(Course $course)
    {
        $course->load(['materials.assignments']);
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate(['title' => 'required', 'level' => 'required']);
        $course->update($request->all());
        return redirect()->back()->with('success', 'Info modul diperbarui.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Modul dihapus.');
    }

    // ==========================================
    //  KELOLA BAB (MATERIALS)
    // ==========================================
    public function storeMaterial(Request $request, $course_id)
    {
        $request->validate(['title' => 'required|string|max:255']);
        Material::create(['course_id' => $course_id, 'title' => $request->title, 'order' => 0]);
        return redirect()->back()->with('success', 'Bab baru ditambahkan!');
    }

    public function updateMaterial(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file_material' => 'nullable|file|mimes:pdf,mp4,doc,docx,ppt,pptx|max:20480',
            'description' => 'nullable|string',
        ]);

        $material = Material::findOrFail($id);
        $dataToUpdate = ['title' => $request->title, 'description' => $request->description];

        if ($request->hasFile('file_material')) {
            if ($material->file_path) Storage::disk('public')->delete($material->file_path);
            $dataToUpdate['file_path'] = $request->file('file_material')->store('materials', 'public');
        }

        $material->update($dataToUpdate);
        return redirect()->back()->with('success', 'Bab materi berhasil diperbarui!');
    }

    public function destroyMaterial($id)
    {
        $material = Material::findOrFail($id);
        if ($material->file_path) Storage::disk('public')->delete($material->file_path);
        $material->delete();
        return redirect()->back()->with('success', 'Bab materi dihapus.');
    }

    // ==========================================
    //  KELOLA TUGAS (ASSIGNMENTS)
    // ==========================================
    public function storeAssignment(Request $request, $material_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file_assignment' => 'nullable|file|mimes:pdf,doc,docx,zip|max:5120',
            'deadline' => 'nullable|date',
        ]);

        $dataToStore = [
            'material_id' => $material_id,
            'title' => $request->title,
            'instruction' => $request->instruction,
            'deadline' => $request->deadline,
        ];

        if ($request->hasFile('file_assignment')) {
            $dataToStore['file_path'] = $request->file('file_assignment')->store('assignments', 'public');
        }

        Assignment::create($dataToStore);
        return redirect()->back()->with('success', 'Tugas baru ditambahkan!');
    }

    public function destroyAssignment($id)
    {
        $assignment = Assignment::findOrFail($id);
        if ($assignment->file_path) Storage::disk('public')->delete($assignment->file_path);
        $assignment->delete();
        return redirect()->back()->with('success', 'Tugas berhasil dihapus.');
    }

    // ==========================================
    //  HALAMAN LIHAT SEMUA TUGAS (DASHBOARD)
    // ==========================================
    public function assignmentsIndex()
    {
        // Ambil semua tugas, urutkan dari yang terbaru
        $assignments = Assignment::with(['material.course'])
                        ->latest()
                        ->paginate(10);

        return view('admin.assignments.index', compact('assignments'));
    }
}