<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\TrainingYear;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    /**
     * Menampilkan daftar modul/materi.
     */
    public function index(Request $request)
    {
        // Ambil data instruktur yang sedang login
        $user = Auth::user();
        
        // Cek apakah user ini terdaftar sebagai teacher
        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return redirect()->back()->with('error', 'Data instruktur tidak ditemukan.');
        }

        // Ambil semua tahun pelatihan
        $trainingYears = TrainingYear::orderBy('name', 'desc')->get();
        
        // Handle filter dari request
        $selectedYearId = $request->get('year', $trainingYears->first()?->id);
        $selectedClassType = $request->get('class_type', 'all');
        
        // Cek akses kelas berdasarkan data teacher
        $canTeachReguler = $teacher->is_reguler ?? false;
        $canTeachKaryawan = $teacher->is_karyawan ?? false;

        // Query modul berdasarkan training_year_id dan class_type yang sesuai akses instruktur
        $modulesQuery = Module::query();

        // Filter berdasarkan tahun pelatihan
        if ($selectedYearId) {
            $modulesQuery->where('training_year_id', $selectedYearId);
        }

        // Filter berdasarkan class_type yang dipilih user
        if ($selectedClassType !== 'all') {
            $modulesQuery->where(function($query) use ($selectedClassType) {
                $query->where('class_type', $selectedClassType)
                      ->orWhere('class_type', 'both');
            });
        }

        // Filter berdasarkan akses kelas instruktur
        if ($canTeachReguler && !$canTeachKaryawan) {
            // Hanya bisa lihat reguler dan both
            $modulesQuery->whereIn('class_type', ['reguler', 'both']);
        } elseif ($canTeachKaryawan && !$canTeachReguler) {
            // Hanya bisa lihat karyawan dan both
            $modulesQuery->whereIn('class_type', ['karyawan', 'both']);
        }
        // Jika keduanya true atau keduanya false, tampilkan semua

        $modules = $modulesQuery->latest()->paginate(10)->appends($request->query());

        return view('instructor.modules.index', compact(
            'modules',
            'trainingYears',
            'selectedYearId',
            'selectedClassType',
            'canTeachReguler',
            'canTeachKaryawan'
        ));
    }

    /**
     * Menampilkan form tambah modul baru.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        // Ambil daftar kursus/kelas milik instruktur ini untuk dropdown
        $courses = Course::where('teacher_id', $teacher->id)->get();

        // Ambil semua tahun pelatihan
        $trainingYears = TrainingYear::orderBy('name', 'desc')->get();
        
        // Handle filter dari request
        $selectedYearId = $request->get('year', $trainingYears->first()?->id);
        
        // Cek akses kelas berdasarkan data teacher
        $canTeachReguler = $teacher->is_reguler ?? false;
        $canTeachKaryawan = $teacher->is_karyawan ?? false;

        return view('instructor.modules.create', compact(
            'courses',
            'trainingYears',
            'selectedYearId',
            'canTeachReguler',
            'canTeachKaryawan'
        ));
    }

    /**
     * Menyimpan modul baru ke database.
     */
    public function store(Request $request)
    {
        // 1. VALIDASI (Batas 50MB / 51200 KB)
        $request->validate([
            'training_year_id' => 'required|exists:training_years,id',
            'class_type'       => 'required|in:all,reguler,karyawan',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'file'             => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png',
                'max:51200' // <--- 50MB
            ],
        ], [
            'training_year_id.required' => 'Tahun periode wajib dipilih.',
            'class_type.required' => 'Tipe kelas wajib dipilih.',
            'title.required' => 'Judul materi wajib diisi.',
            'file.max' => 'Ukuran file terlalu besar! Maksimal 50MB.',
            'file.mimes' => 'Format file tidak didukung.',
        ]);

        try {
            // 2. UPLOAD FILE (jika ada)
            $filePath = null;
            $fileType = null;
            $originalFilename = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('modules', 'public');
                $fileType = $request->file('file')->getClientOriginalExtension();
                $originalFilename = $request->file('file')->getClientOriginalName();
            }

            // 3. Handle class_type (convert 'all' to 'both' for database)
            $classType = $request->class_type === 'all' ? 'both' : $request->class_type;

            // 4. SIMPAN KE DB
            Module::create([
                'training_year_id'  => $request->training_year_id,
                'class_type'        => $classType,
                'title'             => $request->title,
                'description'       => $request->description,
                'file_path'         => $filePath,
                'original_filename' => $originalFilename,
                'file_type'         => $fileType ?? 'pdf',
            ]);

            return redirect()->route('instructor.modules.index')
                             ->with('success', 'Materi berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Hapus file jika gagal simpan DB
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail modul (opsional).
     */
    public function show($id)
    {
        $module = Module::findOrFail($id);
        return view('instructor.modules.show', compact('module'));
    }

    /**
     * Menampilkan form edit modul.
     */
    public function edit($id)
    {
        $module = Module::findOrFail($id);
        
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        // Ambil semua tahun pelatihan
        $trainingYears = TrainingYear::orderBy('name', 'desc')->get();
        
        // Cek akses kelas berdasarkan data teacher
        $canTeachReguler = $teacher->is_reguler ?? false;
        $canTeachKaryawan = $teacher->is_karyawan ?? false;

        return view('instructor.modules.edit', compact(
            'module',
            'trainingYears',
            'canTeachReguler',
            'canTeachKaryawan'
        ));
    }

    /**
     * Mengupdate modul yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $module = Module::findOrFail($id);

        // 1. VALIDASI UPDATE
        $request->validate([
            'training_year_id' => 'required|exists:training_years,id',
            'class_type'       => 'required|in:all,reguler,karyawan',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'file'             => [
                'nullable', // File boleh kosong jika tidak ingin diganti
                'file',
                'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png',
                'max:51200' // <--- 50MB
            ],
        ], [
            'training_year_id.required' => 'Tahun periode wajib dipilih.',
            'class_type.required' => 'Tipe kelas wajib dipilih.',
            'title.required' => 'Judul materi wajib diisi.',
            'file.max' => 'Ukuran file terlalu besar! Maksimal 50MB.',
            'file.mimes' => 'Format file tidak didukung.',
        ]);

        try {
            // Handle class_type (convert 'all' to 'both' for database)
            $classType = $request->class_type === 'all' ? 'both' : $request->class_type;

            // Update data
            $module->training_year_id = $request->training_year_id;
            $module->class_type = $classType;
            $module->title = $request->title;
            $module->description = $request->description;

            // CEK JIKA ADA FILE BARU
            if ($request->hasFile('file')) {
                // Hapus file lama dari storage
                if ($module->file_path && Storage::disk('public')->exists($module->file_path)) {
                    Storage::disk('public')->delete($module->file_path);
                }

                // Upload file baru
                $filePath = $request->file('file')->store('modules', 'public');
                
                // Update info file di DB
                $module->file_path = $filePath;
                $module->original_filename = $request->file('file')->getClientOriginalName();
                $module->file_type = $request->file('file')->getClientOriginalExtension();
            }

            $module->save();

            return redirect()->route('instructor.modules.index')
                             ->with('success', 'Materi berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update materi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus modul dan filenya.
     */
    public function destroy($id)
    {
        $module = Module::findOrFail($id);

        try {
            // 1. Hapus file fisik di storage
            if ($module->file_path && Storage::disk('public')->exists($module->file_path)) {
                Storage::disk('public')->delete($module->file_path);
            }

            // 2. Hapus record di database
            $module->delete();

            return redirect()->route('instructor.modules.index')
                             ->with('success', 'Modul berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus modul: ' . $e->getMessage());
        }
    }
}