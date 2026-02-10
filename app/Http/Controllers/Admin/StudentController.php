<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\TrainingYear;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
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
     * Convert date format from dd/mm/yyyy to Y-m-d for Laravel validation
     */
    private function convertStudentDateFormat(Request $request): void
    {
        foreach (['entry_date', 'birth_date'] as $field) {
            if ($request->filled($field) && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $request->$field)) {
                $date = \DateTime::createFromFormat('d/m/Y', $request->$field);
                if ($date) {
                    $request->merge([$field => $date->format('Y-m-d')]);
                }
            }
        }
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
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%");
            })->orWhere('phone', 'LIKE', "%{$search}%")
              ->orWhere('address', 'LIKE', "%{$search}%")
              ->orWhere('birth_place', 'LIKE', "%{$search}%");
        }

        if ($request->has('year')) {
            $query->where('training_year_id', $request->year);
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

        // Training years for dropdown
        $trainingYears = TrainingYear::orderByDesc('name')->get();
        $selectedTrainingYearId = request('year') ?: $trainingYears->first()?->id;

        return view('admin.students.create', [
            'type' => $type,
            'trainingYears' => $trainingYears,
            'selectedTrainingYearId' => $selectedTrainingYearId,
            'selectedYearId' => $selectedTrainingYearId, // for back button
        ]);
    }

    public function store(Request $request)
    {
        // Convert dd/mm/yyyy to Y-m-d for Laravel validation
        $this->convertStudentDateFormat($request);

        // 1. Validasi Dasar
        $request->validate([
            // User Data
            'name' => 'required|string|max:255',
            'nik' => ['required', 'string', 'size:16', 'regex:/^\d{16}$/', 'unique:users,nik'],
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:8',
            
            // Student Data
            'classroom' => 'required|string',
            'type' => 'required|in:reguler,karyawan',
            'training_year_id' => 'nullable|exists:training_years,id',
            'entry_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P,Laki-laki,Perempuan',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus tepat 16 digit.',
            'nik.regex' => 'NIK harus berupa 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar di sistem.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'classroom.required' => 'Kelas wajib dipilih.',
            'type.required' => 'Tipe kelas wajib dipilih.',
            'entry_date.date' => 'Format tanggal masuk tidak valid.',
            'birth_date.date' => 'Format tanggal lahir tidak valid.',
        ]);

        // 2. Normalisasi Gender
        if (method_exists($this, 'normalizeGender')) {
            $request->merge([
                'gender' => $this->normalizeGender($request->gender),
            ]);
        }

        // 3. Proses Simpan ke Database
        DB::transaction(function () use ($request) {
            // A. Buat Akun User Login dengan NIK
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'nik' => $request->nik,
                'password' => Hash::make($request->password),
                'role' => 'student',
            ]);

            // B. Generate NIS Otomatis (Unik) - internal ID
            $nis = 'AUTO-' . now()->format('YmdHisv');
            while (Student::where('nis', $nis)->exists()) {
                $nis = 'AUTO-' . now()->format('YmdHisv');
            }

            // C. Buat Data Profil Siswa
            Student::create([
                'user_id' => $user->id,
                'nis' => $nis,
                'classroom' => $request->classroom,
                'type' => $request->type,
                'training_year_id' => $request->training_year_id,
                'entry_date' => $request->entry_date,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
            ]);
        });

        return redirect()->route('admin.classes.' . $request->type, ['year' => $request->training_year_id])
            ->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $student = Student::with('user')->findOrFail($id);

        // Training years for dropdown
        $trainingYears = TrainingYear::orderByDesc('name')->get();
        $selectedTrainingYearId = $student->training_year_id;

        return view('admin.students.edit', [
            'student' => $student,
            'trainingYears' => $trainingYears,
            'selectedTrainingYearId' => $selectedTrainingYearId,
        ]);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;

        // Convert dd/mm/yyyy to Y-m-d for Laravel validation
        $this->convertStudentDateFormat($request);

        // 1. Validasi Dasar
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => ['required', 'string', 'size:16', 'regex:/^\d{16}$/', Rule::unique('users')->ignore($user->id)],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',

            'classroom' => 'required|string',
            'type' => 'required|in:reguler,karyawan',
            'training_year_id' => 'nullable|exists:training_years,id',
            'entry_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P,Laki-laki,Perempuan',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus tepat 16 digit.',
            'nik.regex' => 'NIK harus berupa 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar di sistem.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'classroom.required' => 'Kelas wajib dipilih.',
            'type.required' => 'Tipe kelas wajib dipilih.',
            'entry_date.date' => 'Format tanggal masuk tidak valid.',
            'birth_date.date' => 'Format tanggal lahir tidak valid.',
        ]);

        // 2. Normalisasi Gender
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
                'nik' => $request->nik,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // B. Update Data Profil Siswa
            $student->update([
                'classroom' => $request->classroom,
                'type' => $request->type,
                'training_year_id' => $request->training_year_id,
                'entry_date' => $request->entry_date,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
            ]);
        });

        return redirect()->route('admin.classes.' . $request->type, ['year' => $request->training_year_id])
            ->with('success', 'Data siswa berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;
        $type = $student->type;
        $yearId = $student->training_year_id;

        DB::transaction(function () use ($student, $user) {
            $student->delete();
            if($user) $user->delete();
        });

        return redirect()->route('admin.classes.' . $type, ['year' => $yearId])
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}