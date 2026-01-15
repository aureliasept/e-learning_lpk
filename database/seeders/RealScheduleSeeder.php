<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RealScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $instruktur = User::where('role', 'instruktur')->first();

        if (! $instruktur) {
            return;
        }

        $slots = [
            ['08:00:00', '08:30:00'],
            ['08:30:00', '10:30:00'],
            ['10:45:00', '11:45:00'],
            ['13:00:00', '14:45:00'],
            ['15:10:00', '16:00:00'],
        ];

        $daySubjects = [
            'Senin' => ['Aisatsu', 'Taiiku (Olahraga)', 'Bunpou', 'Materi', 'Kaiwa Renshuu'],
            'Selasa' => ['Aisatsu', 'Bab Kotoba', 'Bunpou', 'Bab Kotoba', 'Kaiwa'],
            'Rabu' => ['Taiiku', 'Materi (Hafalan)', 'Psikologi', 'Materi', 'Kaiwa'],
            'Kamis' => ['Kotoba Bunpou', 'Jishu (Belajar Mandiri)', 'Materi', 'Kotoba', 'Kaiwa'],
            'Jumat' => ['Aisatsu', 'Bab Kotoba', 'Materi', 'Renshuu', 'Taiiku'],
        ];

        foreach ($daySubjects as $day => $subjects) {
            foreach ($subjects as $idx => $subject) {
                [$start, $end] = $slots[$idx];

                $course = $this->firstOrCreateCourseForSubject($instruktur->id, $subject);

                Schedule::create([
                    'course_id' => $course->id,
                    'day' => $day,
                    'start_time' => $start,
                    'end_time' => $end,
                    'classroom_name' => 'Kelas 101',
                ]);
            }
        }
    }

    private function firstOrCreateCourseForSubject(int $teacherUserId, string $subject): Course
    {
        $query = Course::query();

        if (Schema::hasColumn('courses', 'teacher_id')) {
            $query->where('teacher_id', $teacherUserId);
        } elseif (Schema::hasColumn('courses', 'instruktur_id')) {
            $query->where('instruktur_id', $teacherUserId);
        }

        if (Schema::hasColumn('courses', 'name')) {
            $query->where('name', $subject);
        } elseif (Schema::hasColumn('courses', 'title')) {
            $query->where('title', $subject);
        } elseif (Schema::hasColumn('courses', 'nama')) {
            $query->where('nama', $subject);
        }

        $course = $query->first();

        if ($course) {
            return $course;
        }

        $course = new Course();

        if (Schema::hasColumn('courses', 'name')) {
            $course->name = $subject;
        }

        if (Schema::hasColumn('courses', 'title')) {
            $course->title = $subject;
        }

        if (Schema::hasColumn('courses', 'nama')) {
            $course->nama = $subject;
        }

        if (Schema::hasColumn('courses', 'description')) {
            $course->description = 'Mata pelajaran: ' . $subject;
        }

        if (Schema::hasColumn('courses', 'deskripsi')) {
            $course->deskripsi = 'Mata pelajaran: ' . $subject;
        }

        if (Schema::hasColumn('courses', 'category')) {
            $course->category = 'reguler';
        }

        if (Schema::hasColumn('courses', 'level')) {
            $course->level = 'pemula';
        }

        if (Schema::hasColumn('courses', 'teacher_id')) {
            $course->teacher_id = $teacherUserId;
        }

        if (Schema::hasColumn('courses', 'instruktur_id')) {
            $course->instruktur_id = $teacherUserId;
        }

        $course->save();

        return $course;
    }
}
