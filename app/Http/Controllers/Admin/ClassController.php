<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\TrainingBatch;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function reguler(Request $request)
    {
        return $this->indexByType($request, 'reguler');
    }

    public function karyawan(Request $request)
    {
        return $this->indexByType($request, 'karyawan');
    }

    private function indexByType(Request $request, string $type)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        // Cek apakah di URL ada parameter '?year=' 
        if ($request->has('year')) {
            $selectedYearId = $request->query('year');
        } else {
            $selectedYearId = $activeYear?->id;
        }

        // Get selected batch from URL
        $selectedBatchId = $request->query('batch');

        $years = AcademicYear::orderByDesc('id')->get();
        
        // Load batches that students in the selected academic year belong to
        $batches = collect();
        if ($selectedYearId) {
            // Get distinct training_batch_ids from students in this academic year and type
            $batchIds = Student::where('academic_year_id', $selectedYearId)
                ->where('type', $type)
                ->whereNotNull('training_batch_id')
                ->distinct()
                ->pluck('training_batch_id');
            
            $batches = TrainingBatch::whereIn('id', $batchIds)
                ->orderBy('start_date')
                ->get();
        }

        $studentsQuery = Student::with(['user', 'academicYear', 'trainingBatch'])
            ->where('type', $type);

        // Filter by year
        if ($selectedYearId) {
            $studentsQuery->where('academic_year_id', $selectedYearId);
        }

        // Filter by batch
        if ($selectedBatchId) {
            $studentsQuery->where('training_batch_id', $selectedBatchId);
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $studentsQuery->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Order by name (A-Z), then by entry_date (newest first)
        $students = $studentsQuery
            ->join('users', 'students.user_id', '=', 'users.id')
            ->orderBy('users.name', 'asc')
            ->orderBy('students.entry_date', 'desc')
            ->select('students.*')
            ->paginate(10)
            ->withQueryString();

        $selectedYear = $selectedYearId ? $years->firstWhere('id', (int) $selectedYearId) : null;
        $selectedBatch = $selectedBatchId ? $batches->firstWhere('id', (int) $selectedBatchId) : null;

        return view('admin.classes.' . $type, [
            'students' => $students,
            'type' => $type,
            'years' => $years,
            'batches' => $batches,
            'activeYear' => $activeYear,
            'selectedYear' => $selectedYear,
            'selectedYearId' => $selectedYearId,
            'selectedBatch' => $selectedBatch,
            'selectedBatchId' => $selectedBatchId,
        ]);
    }
}