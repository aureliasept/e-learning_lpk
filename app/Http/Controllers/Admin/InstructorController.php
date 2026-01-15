<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;

class InstructorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'instructor')->with('teacher');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $instructors = $query->latest()->paginate(10);
        return view('admin.instructors.index', compact('instructors'));
    }

    public function create()
    {
        // Training years for chained dropdown
        $trainingYears = \App\Models\TrainingYear::orderByDesc('name')->get();
        $activeYear = \App\Models\TrainingYear::where('is_active', true)->first();
        
        return view('admin.instructors.create', [
            'trainingYears' => $trainingYears,
            'activeYearId' => $activeYear?->id,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'nip' => 'nullable|string',
            'phone' => 'nullable|string',
            'position' => 'nullable|string',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'training_batch_id' => 'nullable|exists:training_batches,id',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'instructor',
            ]);

            $teacherData = [
                'user_id' => $user->id,
            ];

            if (Schema::hasColumn('teachers', 'nip')) {
                $teacherData['nip'] = $request->nip;
            }
            if (Schema::hasColumn('teachers', 'phone')) {
                $teacherData['phone'] = $request->phone;
            }
            if (Schema::hasColumn('teachers', 'position')) {
                $teacherData['position'] = $request->position;
            }
            if (Schema::hasColumn('teachers', 'birth_place')) {
                $teacherData['birth_place'] = $request->birth_place;
            }
            if (Schema::hasColumn('teachers', 'birth_date')) {
                $teacherData['birth_date'] = $request->birth_date;
            }
            if (Schema::hasColumn('teachers', 'is_reguler')) {
                $teacherData['is_reguler'] = $request->boolean('is_reguler');
            }
            if (Schema::hasColumn('teachers', 'is_karyawan')) {
                $teacherData['is_karyawan'] = $request->boolean('is_karyawan');
            }
            // NEW: training_batch_id for batch assignment
            if (Schema::hasColumn('teachers', 'training_batch_id')) {
                $teacherData['training_batch_id'] = $request->training_batch_id;
            }

            Teacher::create($teacherData);
        });

        return redirect()->route('admin.instructors.index')->with('success', 'Instruktur berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $instructor = User::where('role', 'instructor')->with('teacher')->findOrFail($id);
        
        // Training years for chained dropdown
        $trainingYears = \App\Models\TrainingYear::orderByDesc('name')->get();
        
        // Get current batch info
        $selectedBatchId = $instructor->teacher?->training_batch_id;
        $selectedBatch = $selectedBatchId ? \App\Models\TrainingBatch::find($selectedBatchId) : null;
        $selectedTrainingYearId = $selectedBatch?->training_year_id;
        
        // Get batches for selected year
        $trainingBatches = $selectedTrainingYearId 
            ? \App\Models\TrainingBatch::where('training_year_id', $selectedTrainingYearId)->orderBy('start_date')->get()
            : collect();
        
        return view('admin.instructors.edit', [
            'instructor' => $instructor,
            'trainingYears' => $trainingYears,
            'trainingBatches' => $trainingBatches,
            'selectedTrainingYearId' => $selectedTrainingYearId,
            'selectedBatchId' => $selectedBatchId,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'instructor')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'nip' => 'nullable|string',
            'phone' => 'nullable|string',
            'position' => 'nullable|string',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'training_batch_id' => 'nullable|exists:training_batches,id',
        ]);

        DB::transaction(function () use ($request, $user) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            
            $user->update($userData);

            $teacherData = [];

            if (Schema::hasColumn('teachers', 'nip')) {
                $teacherData['nip'] = $request->nip;
            }
            if (Schema::hasColumn('teachers', 'phone')) {
                $teacherData['phone'] = $request->phone;
            }
            if (Schema::hasColumn('teachers', 'position')) {
                $teacherData['position'] = $request->position;
            }
            if (Schema::hasColumn('teachers', 'birth_place')) {
                $teacherData['birth_place'] = $request->birth_place;
            }
            if (Schema::hasColumn('teachers', 'birth_date')) {
                $teacherData['birth_date'] = $request->birth_date;
            }
            if (Schema::hasColumn('teachers', 'is_reguler')) {
                $teacherData['is_reguler'] = $request->boolean('is_reguler');
            }
            if (Schema::hasColumn('teachers', 'is_karyawan')) {
                $teacherData['is_karyawan'] = $request->boolean('is_karyawan');
            }
            // NEW: training_batch_id for batch assignment
            if (Schema::hasColumn('teachers', 'training_batch_id')) {
                $teacherData['training_batch_id'] = $request->training_batch_id;
            }

            $user->teacher()->updateOrCreate(
                ['user_id' => $user->id],
                $teacherData
            );
        });

        return redirect()->route('admin.instructors.index')->with('success', 'Data instruktur diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::where('role', 'instructor')->findOrFail($id);
        DB::transaction(function () use ($user) {
            $user->teacher()->delete();
            $user->delete();
        });

        return redirect()->route('admin.instructors.index')->with('success', 'Instruktur berhasil dihapus.');
    }
}