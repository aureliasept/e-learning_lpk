<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingYear;
use App\Models\TrainingBatch;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class TrainingYearController extends Controller
{
    /**
     * Level 1: Display table of training years.
     */
    public function index()
    {
        $years = TrainingYear::withCount('batches')
            ->orderByDesc('name')
            ->orderByDesc('id')
            ->get();

        // Calculate stats
        $stats = [
            'total_tahun' => $years->count(),
            'tahun_aktif' => $years->where('is_active', true)->count(),
            'total_gelombang' => TrainingBatch::count(),
            'total_siswa' => \App\Models\Student::whereNotNull('training_batch_id')->count(),
        ];

        return view('admin.training_years.index', compact('years', 'stats'));
    }

    /**
     * Level 2: Show batches for a specific year (Card grid).
     */
    public function show(TrainingYear $training_year)
    {
        $batches = $training_year->batches()
            ->withCount('students')
            ->orderBy('start_date')
            ->get();

        return view('admin.training_years.show', [
            'year' => $training_year,
            'batches' => $batches,
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
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $isActive = $request->boolean('is_active');

            if ($isActive) {
                TrainingYear::where('is_active', true)->update(['is_active' => false]);
            }

            TrainingYear::create([
                'name' => $validated['name'],
                'is_active' => $isActive,
            ]);
        });

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
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $request, $training_year) {
            $isActive = $request->boolean('is_active');

            if ($isActive) {
                TrainingYear::where('is_active', true)
                    ->where('id', '!=', $training_year->id)
                    ->update(['is_active' => false]);
            }

            $training_year->update([
                'name' => $validated['name'],
                'is_active' => $isActive,
            ]);
        });

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

    /**
     * Set a training year as active.
     */
    public function setActive(TrainingYear $training_year): RedirectResponse
    {
        DB::transaction(function () use ($training_year) {
            TrainingYear::where('is_active', true)->update(['is_active' => false]);
            $training_year->update(['is_active' => true]);
        });

        return redirect()->route('admin.training_years.index')
            ->with('success', 'Tahun aktif berhasil diubah.');
    }
}
