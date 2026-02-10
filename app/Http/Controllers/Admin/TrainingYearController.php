<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TrainingYearController extends Controller
{
    /**
     * Display table of training years.
     */
    public function index()
    {
        $years = TrainingYear::withCount('students')
            ->orderByDesc('name')
            ->orderByDesc('id')
            ->get();

        // Calculate stats (simplified - no more batches)
        $stats = [
            'total_tahun' => $years->count(),
            'total_siswa' => Student::whereNotNull('training_year_id')->count(),
        ];

        return view('admin.training_years.index', compact('years', 'stats'));
    }

    /**
     * Show students for a specific year - grouped by class type.
     */
    public function show(TrainingYear $training_year)
    {
        $students = $training_year->students()
            ->with('user')
            ->orderBy('classroom')
            ->orderBy('type')
            ->get();

        $regulerCount = $students->where('type', 'reguler')->count();
        $karyawanCount = $students->where('type', 'karyawan')->count();

        return view('admin.training_years.show', [
            'year' => $training_year,
            'students' => $students,
            'regulerCount' => $regulerCount,
            'karyawanCount' => $karyawanCount,
        ]);
    }

    /**
     * Show form to create a new training year.
     */
    public function create()
    {
        return view('admin.training_years.create');
    }

    /**
     * Store a new training year.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        TrainingYear::create([
            'name' => $validated['name'],
        ]);

        return redirect()->route('admin.training_years.index')
            ->with('success', 'Tahun pelatihan berhasil ditambahkan.');
    }

    /**
     * Show form to edit a training year.
     */
    public function edit(TrainingYear $training_year)
    {
        return view('admin.training_years.edit', ['year' => $training_year]);
    }

    /**
     * Update a training year.
     */
    public function update(Request $request, TrainingYear $training_year): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $training_year->update([
            'name' => $validated['name'],
        ]);

        return redirect()->route('admin.training_years.index')
            ->with('success', 'Tahun pelatihan berhasil diperbarui.');
    }

    /**
     * Delete a training year.
     */
    public function destroy(TrainingYear $training_year): RedirectResponse
    {
        $training_year->delete();

        return redirect()->route('admin.training_years.index')
            ->with('success', 'Tahun pelatihan berhasil dihapus.');
    }
}
