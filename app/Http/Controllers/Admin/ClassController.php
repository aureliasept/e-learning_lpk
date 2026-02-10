<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TrainingYear;
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
        // Get all training years (simplified - no batches)
        $years = TrainingYear::orderByDesc('name')->get();
        
        // Get selected year from URL or default to first year
        $selectedYearId = $request->query('year');
        $selectedYear = null;
        
        if ($selectedYearId) {
            $selectedYear = $years->firstWhere('id', (int) $selectedYearId);
        }

        // Query students
        $studentsQuery = Student::with(['user', 'academicYear', 'trainingYear'])
            ->where('type', $type);

        // Filter by training year
        if ($selectedYearId) {
            $studentsQuery->where('training_year_id', $selectedYearId);
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

        return view('admin.classes.' . $type, [
            'students' => $students,
            'type' => $type,
            'years' => $years,
            'selectedYear' => $selectedYear,
            'selectedYearId' => $selectedYearId,
        ]);
    }
}