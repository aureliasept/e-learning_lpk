<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\File;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\StudentController; // Untuk CRUD Siswa & List Kelas
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\TrainingYearController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\CourseController;  // Jika masih pakai course/mapel
use App\Http\Controllers\Admin\ProfileController;

// Other Controllers
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboard;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\ModuleController as InstructorModuleController;
use App\Http\Controllers\Instructor\ChapterController as InstructorChapterController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\NewsController as StudentNewsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. ROOT & AUTH
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
});

Route::get('/media/news/{path}', function (string $path) {
    $path = str_replace('..', '', $path);
    $absolutePath = storage_path('app/public/' . $path);

    if (!File::exists($absolutePath)) {
        abort(404);
    }

    return response()->file($absolutePath);
})->where('path', '.*')->name('media.news');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 2. DASHBOARD REDIRECTOR
Route::get('/dashboard', function () {
    if (!auth()->check()) return redirect()->route('login');
    
    return match (auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'instructor', 'instruktur' => redirect()->route('instructor.dashboard'),
        'student' => redirect()->route('student.dashboard'),
        default => redirect('/login'),
    };
})->middleware('auth')->name('dashboard');


// 3. GROUP ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Profil Admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Manajemen Instruktur (CRUD Lengkap)
    Route::resource('instructors', InstructorController::class);
    
    // Manajemen Berita (CRUD Lengkap)
    Route::resource('news', NewsController::class);

    // Periode Pelatihan - Simplified (Tahun Pelatihan / Angkatan only, no batches)
    Route::resource('training_years', TrainingYearController::class);

    // Legacy: Periode Pelatihan / Tahun Ajaran (kept for backward compatibility)
    Route::resource('academic_years', AcademicYearController::class)->except(['show']);
    Route::patch('academic_years/{academic_year}/set-active', [AcademicYearController::class, 'setActive'])->name('academic_years.set_active');

    // Manajemen Kelas
    Route::get('/classes/reguler', [ClassController::class, 'reguler'])->name('classes.reguler');
    Route::get('/classes/karyawan', [ClassController::class, 'karyawan'])->name('classes.karyawan');

    // Manajemen Siswa (akses via menu kelas)
    Route::resource('students', StudentController::class)->except(['index', 'show']);
    
    // Manajemen Mata Pelajaran/Course (Opsional, jika ada)
    Route::resource('courses', CourseController::class);
});


// 4. GROUP INSTRUCTOR
Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [InstructorDashboard::class, 'index'])->name('dashboard');

    // Profil Instruktur
    Route::get('/profile', [\App\Http\Controllers\Instructor\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\Instructor\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [\App\Http\Controllers\Instructor\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Kelas Saya (Daftar Siswa)
    Route::get('/students', [\App\Http\Controllers\Instructor\StudentController::class, 'index'])->name('students.index');

    // Berita (View Only)
    Route::get('/news/{news:slug}', [\App\Http\Controllers\Instructor\NewsController::class, 'show'])->name('news.show');

    // Modul (courses)
    Route::resource('courses', InstructorCourseController::class)->only(['index', 'show']);

    Route::resource('modules', InstructorModuleController::class)->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy',
    ]);

    Route::resource('chapters', InstructorChapterController::class)->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy',
    ]);

    // Quiz Management (CBT System)
    Route::resource('quizzes', \App\Http\Controllers\Instructor\QuizController::class);
    Route::patch('quizzes/{quiz}/toggle-active', [\App\Http\Controllers\Instructor\QuizController::class, 'toggleActive'])->name('quizzes.toggle_active');
    Route::get('quizzes-template/download', [\App\Http\Controllers\Instructor\QuizController::class, 'downloadTemplate'])->name('quizzes.template');
    Route::post('quizzes/{quiz}/import', [\App\Http\Controllers\Instructor\QuizController::class, 'importQuestions'])->name('quizzes.import');
    Route::post('quizzes/{quiz}/regenerate-code', [\App\Http\Controllers\Instructor\QuizController::class, 'regenerateCode'])->name('quizzes.regenerate_code');

    // Papan Instruksi (Instruction Board) - Standalone with filtering
    Route::get('instructions', [\App\Http\Controllers\Instructor\InstructionController::class, 'index'])->name('instructions.index');
    Route::get('instructions/create', [\App\Http\Controllers\Instructor\InstructionController::class, 'create'])->name('instructions.create');
    Route::post('instructions', [\App\Http\Controllers\Instructor\InstructionController::class, 'store'])->name('instructions.store');
    Route::get('instructions/{instruction}', [\App\Http\Controllers\Instructor\InstructionController::class, 'show'])->name('instructions.show');
    Route::get('instructions/{instruction}/edit', [\App\Http\Controllers\Instructor\InstructionController::class, 'edit'])->name('instructions.edit');
    Route::put('instructions/{instruction}', [\App\Http\Controllers\Instructor\InstructionController::class, 'update'])->name('instructions.update');
    Route::delete('instructions/{instruction}', [\App\Http\Controllers\Instructor\InstructionController::class, 'destroy'])->name('instructions.destroy');
    Route::post('instructions/submissions/{submission}/grade', [\App\Http\Controllers\Instructor\InstructionController::class, 'grade'])->name('instructions.grade');
});


// 5. GROUP STUDENT
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Student\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\Student\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [\App\Http\Controllers\Student\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Papan Instruksi (Instruction Board)
    Route::get('/instructions', [\App\Http\Controllers\Student\InstructionController::class, 'index'])->name('instructions.index');
    Route::get('/instructions/{instruction}', [\App\Http\Controllers\Student\InstructionController::class, 'show'])->name('instructions.show');
    Route::post('/instructions/{instruction}/submit', [\App\Http\Controllers\Student\InstructionController::class, 'submit'])->name('instructions.submit');

    // News
    Route::get('/news', [StudentNewsController::class, 'index'])->name('news.index');
    Route::get('/news/{news:slug}', [StudentNewsController::class, 'show'])->name('news.show');

    // Exam (CBT System)
    Route::get('/exam', [\App\Http\Controllers\Student\ExamController::class, 'index'])->name('exam.index');
    Route::get('/exam/{quiz}/verify', [\App\Http\Controllers\Student\ExamController::class, 'showVerify'])->name('exam.verify');
    Route::post('/exam/{quiz}/verify', [\App\Http\Controllers\Student\ExamController::class, 'verifyCode'])->name('exam.verify.submit');
    Route::get('/exam/{quiz}/start', [\App\Http\Controllers\Student\ExamController::class, 'start'])->name('exam.start');
    Route::post('/exam/{quiz}/submit', [\App\Http\Controllers\Student\ExamController::class, 'submit'])->name('exam.submit');
    Route::post('/exam/save/{attempt}', [\App\Http\Controllers\Student\ExamController::class, 'saveAnswers'])->name('exam.save');
    Route::get('/exam/result/{attempt}', [\App\Http\Controllers\Student\ExamController::class, 'result'])->name('exam.result');

    // Gudang Materi (Modules)
    Route::get('/modules', [\App\Http\Controllers\Student\ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/{module}', [\App\Http\Controllers\Student\ModuleController::class, 'show'])->name('modules.show');
    Route::get('/modules/{module}/download', [\App\Http\Controllers\Student\ModuleController::class, 'download'])->name('modules.download');
});