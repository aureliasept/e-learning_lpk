<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingBatch;
use App\Models\TrainingYear;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class TrainingBatchController extends Controller
{
    /**
     * Level 3: Show students in a specific batch.
     */
    public function show(Request $request, TrainingBatch $training_batch)
    {
        $selectedType = $request->query('type', '');
        
        $studentsQuery = $training_batch->students()
            ->with('user');
        
        // Filter by type if specified
        if ($selectedType) {
            $studentsQuery->where('type', $selectedType);
        }
        
        // Order by name (A-Z), then by entry_date (newest first)
        $students = $studentsQuery
            ->join('users', 'students.user_id', '=', 'users.id')
            ->orderBy('users.name', 'asc')
            ->orderBy('students.entry_date', 'desc')
            ->select('students.*')
            ->paginate(15)
            ->withQueryString();

        return view('admin.training_batches.show', [
            'batch' => $training_batch,
            'year' => $training_batch->trainingYear,
            'students' => $students,
            'selectedType' => $selectedType,
        ]);
    }

    /**
     * Show form to create a new batch.
     */
    public function create(Request $request)
    {
        $yearId = $request->get('year');
        $year = $yearId ? TrainingYear::find($yearId) : null;
        $years = TrainingYear::orderByDesc('name')->get();

        return view('admin.training_batches.create', [
            'year' => $year,
            'years' => $years,
        ]);
    }

    /**
     * Store a new batch.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'training_year_id' => ['required', 'exists:training_years,id'],
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        TrainingBatch::create($validated);

        return redirect()->route('admin.training_years.show', $validated['training_year_id'])
            ->with('success', 'Gelombang berhasil ditambahkan.');
    }

    /**
     * Show form to edit a batch.
     */
    public function edit(TrainingBatch $training_batch)
    {
        $years = TrainingYear::orderByDesc('name')->get();

        return view('admin.training_batches.edit', [
            'batch' => $training_batch,
            'years' => $years,
        ]);
    }

    /**
     * Update a batch.
     */
    public function update(Request $request, TrainingBatch $training_batch): RedirectResponse
    {
        $validated = $request->validate([
            'training_year_id' => ['required', 'exists:training_years,id'],
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $training_batch->update($validated);

        return redirect()->route('admin.training_years.show', $training_batch->training_year_id)
            ->with('success', 'Gelombang berhasil diperbarui.');
    }

    /**
     * Delete a batch.
     */
    public function destroy(TrainingBatch $training_batch): RedirectResponse
    {
        $yearId = $training_batch->training_year_id;
        $training_batch->delete();

        return redirect()->route('admin.training_years.show', $yearId)
            ->with('success', 'Gelombang berhasil dihapus.');
    }

    /**
     * API: Get batches by year for chained dropdown.
     */
    public function getByYear(TrainingYear $year): JsonResponse
    {
        $batches = $year->batches()
            ->orderBy('start_date')
            ->get(['id', 'name', 'start_date', 'end_date']);

        return response()->json($batches);
    }
}
