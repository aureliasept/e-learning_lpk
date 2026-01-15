<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function showSubmissions(Assignment $assignment): View
    {
        $assignment->load(['material.course', 'submissions.user']);

        $course = $assignment->material?->course;
        abort_if(! $course, 404);

        $userId = Auth::id();

        if (Schema::hasColumn('courses', 'teacher_id') && ($course->teacher_id ?? null) !== $userId) {
            abort(403);
        }

        if (Schema::hasColumn('courses', 'instruktur_id') && ($course->instruktur_id ?? null) !== $userId) {
            abort(403);
        }

        $enrollments = Enrollment::query()
            ->with('student')
            ->where('course_id', $course->id)
            ->get();

        $students = $enrollments
            ->map(fn ($enrollment) => $enrollment->student)
            ->filter()
            ->unique('id')
            ->values();

        $submissionsByUserId = $assignment->submissions->keyBy('user_id');

        $rows = $students->map(function ($student) use ($submissionsByUserId) {
            return [
                'student' => $student,
                'submission' => $submissionsByUserId->get($student->id),
            ];
        });

        return view('instruktur.assignments.submissions', [
            'assignment' => $assignment,
            'course' => $course,
            'rows' => $rows,
        ]);
    }

    public function gradeSubmission(Request $request, AssignmentSubmission $submission)
    {
        $submission->loadMissing('assignment.material.course');

        $course = $submission->assignment?->material?->course;
        abort_if(! $course, 404);

        $userId = Auth::id();

        if (Schema::hasColumn('courses', 'teacher_id') && ($course->teacher_id ?? null) !== $userId) {
            abort(403);
        }

        if (Schema::hasColumn('courses', 'instruktur_id') && ($course->instruktur_id ?? null) !== $userId) {
            abort(403);
        }

        $validated = $request->validate([
            'grade' => ['nullable', 'integer', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string'],
        ]);

        $submission->update([
            'grade' => $validated['grade'],
            'feedback' => $validated['feedback'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Nilai & feedback berhasil disimpan.');
    }
}
