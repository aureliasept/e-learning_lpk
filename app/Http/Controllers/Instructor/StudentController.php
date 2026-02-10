<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
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
        
        // Get all training years
        $trainingYears = TrainingYear::orderBy('name', 'desc')->get();
        
        // Get selected year from request
        $selectedYearId = $request->get('year', $trainingYears->first()?->id);
        $selectedClassType = $request->get('class', 'reguler'); // Default to reguler
        
        // Get selected year
        $selectedYear = $trainingYears->firstWhere('id', $selectedYearId);
        
        // Determine what classes instructor can teach
        $canTeachReguler = $teacher ? $teacher->is_reguler : false;
        $canTeachKaryawan = $teacher ? $teacher->is_karyawan : false;
        
        // Get students for selected year and class type
        $regulerStudents = collect();
        $karyawanStudents = collect();
        
        if ($selectedYearId) {
            $regulerStudents = Student::where('training_year_id', $selectedYearId)
                ->where('type', 'reguler')
                ->with('user')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->select('students.*')
                ->get();
            
            $karyawanStudents = Student::where('training_year_id', $selectedYearId)
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
            'selectedClassType',
            'canTeachReguler',
            'canTeachKaryawan',
            'regulerStudents',
            'karyawanStudents'
        ));
    }
}
