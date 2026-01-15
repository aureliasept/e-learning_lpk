<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TrainingBatch;
use App\Models\TrainingYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get teacher record for this instructor
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        // Get all training years with their batches
        $trainingYears = TrainingYear::with('batches')
            ->orderBy('name', 'desc')
            ->get();
        
        // Get selected period and batch from request
        $selectedYearId = $request->get('year', $trainingYears->first()?->id);
        $selectedBatchId = $request->get('batch');
        $selectedClassType = $request->get('class', 'reguler'); // Default to reguler
        
        // Get selected year
        $selectedYear = $trainingYears->firstWhere('id', $selectedYearId);
        
        // Get batches for selected year
        $batches = $selectedYear ? $selectedYear->batches : collect();
        
        // Get selected batch (default to first if not specified)
        $selectedBatch = null;
        if ($selectedBatchId) {
            $selectedBatch = $batches->firstWhere('id', $selectedBatchId);
        } elseif ($batches->count() > 0) {
            $selectedBatch = $batches->first();
            $selectedBatchId = $selectedBatch->id;
        }
        
        // Determine what classes instructor can teach
        $canTeachReguler = $teacher ? $teacher->is_reguler : false;
        $canTeachKaryawan = $teacher ? $teacher->is_karyawan : false;
        
        // Get students for selected batch and class type
        $regulerStudents = collect();
        $karyawanStudents = collect();
        
        if ($selectedBatch) {
            $regulerStudents = Student::where('training_batch_id', $selectedBatch->id)
                ->where('type', 'reguler')
                ->with('user')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->select('students.*')
                ->get();
            
            $karyawanStudents = Student::where('training_batch_id', $selectedBatch->id)
                ->where('type', 'karyawan')
                ->with('user')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->select('students.*')
                ->get();
        }
        
        return view('instructor.students.index', compact(
            'teacher',
            'trainingYears',
            'selectedYear',
            'selectedYearId',
            'batches',
            'selectedBatch',
            'selectedBatchId',
            'selectedClassType',
            'canTeachReguler',
            'canTeachKaryawan',
            'regulerStudents',
            'karyawanStudents'
        ));
    }
}
