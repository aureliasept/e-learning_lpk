<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use App\Models\Material;
use App\Models\Assignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // ambil satu instruktur (sementara pakai user pertama)
        $instruktur = User::where('role', 'instruktur')->first();

        if (! $instruktur) {
            $instruktur = new User();
            $instruktur->name = 'Instruktur Demo';
            $instruktur->email = 'instruktur.demo@example.com';
            $instruktur->password = Hash::make('password');
            $instruktur->role = 'instruktur';
            $instruktur->save();
        }

        $courseQuery = Course::query();

        if (Schema::hasColumn('courses', 'teacher_id')) {
            $courseQuery->where('teacher_id', $instruktur->id);
        } elseif (Schema::hasColumn('courses', 'instruktur_id')) {
            $courseQuery->where('instruktur_id', $instruktur->id);
        }

        if (Schema::hasColumn('courses', 'name')) {
            $courseQuery->where('name', 'Mastering Laravel 12 & Tailwind');
        } elseif (Schema::hasColumn('courses', 'title')) {
            $courseQuery->where('title', 'Mastering Laravel 12 & Tailwind');
        } elseif (Schema::hasColumn('courses', 'nama')) {
            $courseQuery->where('nama', 'Mastering Laravel 12 & Tailwind');
        }

        $course = $courseQuery->first();

        if (! $course) {
            $course = new Course();

            if (Schema::hasColumn('courses', 'name')) {
                $course->name = 'Mastering Laravel 12 & Tailwind';
            }

            if (Schema::hasColumn('courses', 'title')) {
                $course->title = 'Mastering Laravel 12 & Tailwind';
            }

            if (Schema::hasColumn('courses', 'nama')) {
                $course->nama = 'Mastering Laravel 12 & Tailwind';
            }

            if (Schema::hasColumn('courses', 'description')) {
                $course->description = 'Kelas intensif membangun LMS dengan Laravel 12 dan Tailwind.';
            }

            if (Schema::hasColumn('courses', 'deskripsi')) {
                $course->deskripsi = 'Kelas intensif membangun LMS dengan Laravel 12 dan Tailwind.';
            }

            if (Schema::hasColumn('courses', 'category')) {
                $course->category = 'reguler';
            }

            if (Schema::hasColumn('courses', 'level')) {
                $course->level = 'pemula';
            }

            if (Schema::hasColumn('courses', 'teacher_id')) {
                $course->teacher_id = $instruktur->id;
            }

            if (Schema::hasColumn('courses', 'instruktur_id')) {
                $course->instruktur_id = $instruktur->id;
            }

            $course->save();
        }

        if (Schema::hasTable('schedules')) {
            $scheduleData = [];

            if (Schema::hasColumn('schedules', 'course_id')) {
                $scheduleData['course_id'] = $course->id;
            }

            if (Schema::hasColumn('schedules', 'day')) {
                $scheduleData['day'] = 'Senin';
            }

            if (Schema::hasColumn('schedules', 'start_time')) {
                $scheduleData['start_time'] = '08:00:00';
            }

            if (Schema::hasColumn('schedules', 'end_time')) {
                $scheduleData['end_time'] = '10:00:00';
            }

            if (Schema::hasColumn('schedules', 'classroom_name')) {
                $scheduleData['classroom_name'] = 'Ruang Kelas A';
            }

            if ($scheduleData !== []) {
                $scheduleData['created_at'] = now();
                $scheduleData['updated_at'] = now();

                DB::table('schedules')->insert($scheduleData);
            }
        }

        $hasModules = Schema::hasTable('modules');

        for ($i = 1; $i <= 25; $i++) {
            $moduleId = null;
            $materialId = null;

            if ($hasModules) {
                $module = new Module();

                if (Schema::hasColumn('modules', 'course_id')) {
                    $module->course_id = $course->id;
                }

                if (Schema::hasColumn('modules', 'title')) {
                    $module->title = 'Bab ' . $i;
                }

                if (Schema::hasColumn('modules', 'judul')) {
                    $module->judul = 'Bab ' . $i;
                }

                if (Schema::hasColumn('modules', 'description')) {
                    $module->description = 'Materi pembelajaran Bab ' . $i;
                }

                if (Schema::hasColumn('modules', 'deskripsi')) {
                    $module->deskripsi = 'Materi pembelajaran Bab ' . $i;
                }

                if (Schema::hasColumn('modules', 'order')) {
                    $module->order = $i;
                }

                if (Schema::hasColumn('modules', 'urutan')) {
                    $module->urutan = $i;
                }

                $module->save();
                $moduleId = $module->id;
            }

            $material = new Material();

            if (Schema::hasColumn('materials', 'module_id') && $moduleId) {
                $material->module_id = $moduleId;
            }

            if (Schema::hasColumn('materials', 'course_id')) {
                $material->course_id = $course->id;
            }

            if (Schema::hasColumn('materials', 'title')) {
                $material->title = 'Slide Materi Bab ' . $i;
            }

            if (Schema::hasColumn('materials', 'type')) {
                $material->type = 'pdf';
            }

            if (Schema::hasColumn('materials', 'file_path')) {
                $material->file_path = 'dummy/slides/slide-bab-' . $i . '.pdf';
            }

            if (Schema::hasColumn('materials', 'content')) {
                $material->content = 'https://example.com/slides/slide-bab-' . $i . '.pdf';
            }

            if (Schema::hasColumn('materials', 'description')) {
                $material->description = 'Materi PDF untuk Bab ' . $i;
            }

            if (Schema::hasColumn('materials', 'order')) {
                $material->order = $i;
            }

            $material->save();
            $materialId = $material->id;

            $assignment = new Assignment();

            if (Schema::hasColumn('assignments', 'module_id') && $moduleId) {
                $assignment->module_id = $moduleId;
            }

            if (Schema::hasColumn('assignments', 'material_id') && $materialId) {
                $assignment->material_id = $materialId;
            }

            if (Schema::hasColumn('assignments', 'title')) {
                $assignment->title = 'Tugas Praktik Bab ' . $i;
            }

            if (Schema::hasColumn('assignments', 'description')) {
                $assignment->description = 'Kerjakan latihan untuk Bab ' . $i;
            }

            if (Schema::hasColumn('assignments', 'instruction')) {
                $assignment->instruction = 'Kerjakan latihan untuk Bab ' . $i;
            }

            if (Schema::hasColumn('assignments', 'deadline')) {
                $assignment->deadline = now()->addWeek();
            }

            $assignment->save();
        }
    }
}
