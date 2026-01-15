<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    private function resolveAcademicYearId(?string $academicYearId, ?string $entryDate): ?int
    {
        $yearId = $academicYearId ? (int) $academicYearId : null;

        if ($entryDate) {
            $matchedYear = AcademicYear::whereDate('start_date', '<=', $entryDate)
                ->whereDate('end_date', '>=', $entryDate)
                ->orderByDesc('start_date')
                ->first();

            if ($matchedYear) {
                return (int) $matchedYear->id;
            }

            return $yearId;
        }

        return $yearId;
    }

    private function normalizeGender(?string $gender): ?string
    {
        if ($gender === null || $gender === '') {
            return null;
        }

        $gender = trim($gender);

        return match ($gender) {
            'L', 'l', 'Laki-laki', 'Laki - laki', 'Laki laki' => 'L',
            'P', 'p', 'Perempuan' => 'P',
            default => $gender,
        };
    }

    /**
     * Menampilkan daftar siswa berdasarkan tipe kelas.
     */
    public function indexByType(Request $request, $type)
    {
        // Validasi tipe
        if (!in_array($type, ['reguler', 'karyawan'])) {
            abort(404);
        }

        $query = Student::with('user')->where('type', $type);

        // Filter Pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            })->orWhere('phone', 'LIKE', "%{$search}%")
              ->orWhere('address', 'LIKE', "%{$search}%")
              ->orWhere('birth_place', 'LIKE', "%{$search}%");
        }

        if ($request->has('year')) {
            $query->where('academic_year_id', $request->year);
        }

        $students = $query->latest()->paginate(10);
        
        // Memanggil view spesifik sesuai request
        return view('admin.classes.' . $type, compact('students', 'type'));
    }

    public function create()
    {
        $type = request('type');
        if (!in_array($type, ['reguler', 'karyawan'])) {
            $type = 'reguler';
        }

        // Legacy academic years (for backward compatibility)
        $academicYears = AcademicYear::orderByDesc('id')->get();
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = request('year') ?: ($activeYear?->id);

        // New training years/batches for chained dropdown
        $trainingYears = \App\Models\TrainingYear::orderByDesc('name')->get();
        $activeTrainingYear = \App\Models\TrainingYear::where('is_active', true)->first();
        
        // If batch is specified, get that batch's year
        $selectedBatchId = request('batch');
        $selectedBatch = $selectedBatchId ? \App\Models\TrainingBatch::find($selectedBatchId) : null;
        $selectedTrainingYearId = $selectedBatch?->training_year_id ?: ($activeTrainingYear?->id);
        
        // Get batches for selected year
        $trainingBatches = $selectedTrainingYearId 
            ? \App\Models\TrainingBatch::where('training_year_id', $selectedTrainingYearId)->orderBy('start_date')->get()
            : collect();

        return view('admin.students.create', [
            'type' => $type,
            'years' => $academicYears,
            'activeYear' => $activeYear,
            'selectedYearId' => $selectedYearId,
            // New chained dropdown data
            'trainingYears' => $trainingYears,
            'trainingBatches' => $trainingBatches,
            'selectedTrainingYearId' => $selectedTrainingYearId,
            'selectedBatchId' => $selectedBatchId,
        ]);
    }

public function store(Request $request)
    {
        // 1. Validasi Dasar
        $request->validate([
            // User Data
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            
            // Student Data
            'classroom' => 'required|string',
            'type' => 'required|in:reguler,karyawan',
            // Legacy academic_year_id (backward compatibility)
            'academic_year_id' => 'nullable|exists:academic_years,id', 
            // NEW: training_batch_id is now the primary relationship
            'training_batch_id' => 'required|exists:training_batches,id',
            'entry_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P,Laki-laki,Perempuan',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
        ]);

        // 2. Validasi Kustom: entry_date vs batch date range
        if ($request->entry_date && $request->training_batch_id) {
            $batch = \App\Models\TrainingBatch::find($request->training_batch_id);
            if ($batch) {
                $entryDate = \Carbon\Carbon::parse($request->entry_date);
                $batchStart = \Carbon\Carbon::parse($batch->start_date)->startOfDay();
                $batchEnd = \Carbon\Carbon::parse($batch->end_date)->endOfDay();
                
                if ($entryDate->lt($batchStart) || $entryDate->gt($batchEnd)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'entry_date' => 'Tanggal masuk tidak sesuai dengan rentang waktu Gelombang yang dipilih (' 
                            . $batchStart->format('d/m/Y') . ' - ' . $batchEnd->format('d/m/Y') . ').',
                    ]);
                }
            }
        }

        // 3. Normalisasi Gender
        if (method_exists($this, 'normalizeGender')) {
            $request->merge([
                'gender' => $this->normalizeGender($request->gender),
            ]);
        }

        // 4. Proses Simpan ke Database
        DB::transaction(function () use ($request) {
            // A. Buat Akun User Login
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student',
            ]);

            // B. Generate NIS Otomatis (Unik)
            $nis = 'AUTO-' . now()->format('YmdHisv');
            while (Student::where('nis', $nis)->exists()) {
                $nis = 'AUTO-' . now()->format('YmdHisv');
            }

            // C. Buat Data Profil Siswa - PENTING: Simpan training_batch_id
            Student::create([
                'user_id' => $user->id,
                'nis' => $nis,
                'classroom' => $request->classroom,
                'type' => $request->type,
                'academic_year_id' => $request->academic_year_id, // Legacy
                'training_batch_id' => $request->training_batch_id, // NEW: This is the key fix!
                'entry_date' => $request->entry_date,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
            ]);
        });

        // 5. Redirect - if coming from batch detail, go back there
        if ($request->training_batch_id) {
            return redirect()->route('admin.training_batches.show', $request->training_batch_id)
                ->with('success', 'Siswa berhasil ditambahkan.');
        }

        return redirect()->route('admin.classes.' . $request->type, ['year' => $request->academic_year_id])
            ->with('success', 'Siswa berhasil ditambahkan.');
    }
    public function edit($id)
    {
        $student = Student::with('user')->findOrFail($id);
        $years = AcademicYear::orderByDesc('id')->get();
        $activeYear = AcademicYear::where('is_active', true)->first();

        // New training years/batches for chained dropdown
        $trainingYears = \App\Models\TrainingYear::orderByDesc('name')->get();
        
        // Get current student's batch info
        $selectedBatchId = $student->training_batch_id;
        $selectedBatch = $selectedBatchId ? \App\Models\TrainingBatch::find($selectedBatchId) : null;
        $selectedTrainingYearId = $selectedBatch?->training_year_id;
        
        // Get batches for selected year
        $trainingBatches = $selectedTrainingYearId 
            ? \App\Models\TrainingBatch::where('training_year_id', $selectedTrainingYearId)->orderBy('start_date')->get()
            : collect();

        return view('admin.students.edit', [
            'student' => $student,
            'years' => $years,
            'activeYear' => $activeYear,
            // New chained dropdown data
            'trainingYears' => $trainingYears,
            'trainingBatches' => $trainingBatches,
            'selectedTrainingYearId' => $selectedTrainingYearId,
            'selectedBatchId' => $selectedBatchId,
        ]);
    }

public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;

        // 1. Validasi Dasar
        $request->validate([
            'name' => 'required|string|max:255',
            // Validasi email unik kecuali milik user ini sendiri
            'email' => ['required', 'email', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8', // Password nullable saat edit

            'classroom' => 'required|string',
            'type' => 'required|in:reguler,karyawan',
            
            // Legacy academic_year_id (backward compatibility)
            'academic_year_id' => 'nullable|exists:academic_years,id', 
            // NEW: training_batch_id is the primary relationship
            'training_batch_id' => 'required|exists:training_batches,id',
            
            'entry_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P,Laki-laki,Perempuan',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
        ]);

        // 2. Validasi Kustom: entry_date vs batch date range
        if ($request->entry_date && $request->training_batch_id) {
            $batch = \App\Models\TrainingBatch::find($request->training_batch_id);
            if ($batch) {
                $entryDate = \Carbon\Carbon::parse($request->entry_date);
                $batchStart = \Carbon\Carbon::parse($batch->start_date)->startOfDay();
                $batchEnd = \Carbon\Carbon::parse($batch->end_date)->endOfDay();
                
                if ($entryDate->lt($batchStart) || $entryDate->gt($batchEnd)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'entry_date' => 'Tanggal masuk tidak sesuai dengan rentang waktu Gelombang yang dipilih (' 
                            . $batchStart->format('d/m/Y') . ' - ' . $batchEnd->format('d/m/Y') . ').',
                    ]);
                }
            }
        }

        // 3. Normalisasi Gender
        if (method_exists($this, 'normalizeGender')) {
            $request->merge([
                'gender' => $this->normalizeGender($request->gender),
            ]);
        }

        DB::transaction(function () use ($request, $user, $student) {
            // A. Update Data User Login
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Cek: Jika kolom password diisi, enkripsi dan update. Jika kosong, abaikan.
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // B. Update Data Profil Siswa - PENTING: Simpan training_batch_id
            $student->update([
                'classroom' => $request->classroom,
                'type' => $request->type,
                'academic_year_id' => $request->academic_year_id, // Legacy
                'training_batch_id' => $request->training_batch_id, // NEW: This is the key fix!
                'entry_date' => $request->entry_date,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
            ]);
        });

        // Redirect - if training_batch_id exists, go back to batch detail
        if ($request->training_batch_id) {
            return redirect()->route('admin.training_batches.show', $request->training_batch_id)
                ->with('success', 'Data siswa berhasil diperbarui.');
        }

        return redirect()->route('admin.classes.' . $request->type, ['year' => $request->academic_year_id])
            ->with('success', 'Data siswa berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;
        $type = $student->type;
        $yearId = $student->academic_year_id;

        DB::transaction(function () use ($student, $user) {
            $student->delete();
            if($user) $user->delete();
        });

        return redirect()->route('admin.classes.' . $type, ['year' => $yearId])
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}