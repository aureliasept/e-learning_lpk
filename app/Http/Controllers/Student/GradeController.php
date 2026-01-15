<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        $courses = Course::query()
            ->whereIn('id', Enrollment::query()->where('user_id', $userId)->select('course_id'))
            ->latest()
            ->get();

        $rows = $courses->map(function (Course $course) use ($userId) {
            $assignmentIds = Assignment::query()
                ->whereIn('material_id', $course->materials()->select('id'))
                ->pluck('id');

            $assignmentScore = null;
            if ($assignmentIds->count() > 0) {
                $assignmentScore = AssignmentSubmission::query()
                    ->where('user_id', $userId)
                    ->whereIn('assignment_id', $assignmentIds)
                    ->whereNotNull('grade')
                    ->avg('grade');

                $assignmentScore = $assignmentScore !== null ? round((float) $assignmentScore, 2) : null;
            }

            $quizScore = null;

            $finalScore = null;
            $components = collect([$quizScore, $assignmentScore])->filter(fn ($v) => $v !== null);
            if ($components->count() > 0) {
                $finalScore = round((float) ($components->avg()), 2);
            }

            $passingGrade = 75;
            $status = $finalScore === null ? '-' : ($finalScore >= $passingGrade ? 'Lulus' : 'Remedial');

            $courseTitle = $course->name ?? $course->title ?? '-';

            return [
                'course' => $course,
                'courseTitle' => $courseTitle,
                'quizScore' => $quizScore,
                'assignmentScore' => $assignmentScore,
                'finalScore' => $finalScore,
                'status' => $status,
            ];
        });

        return view('student.grades.index', [
            'rows' => $rows,
        ]);
    }
}
