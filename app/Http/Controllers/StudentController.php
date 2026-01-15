<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // TAMPILKAN DAFTAR KELAS (FOLDER)
    public function index()
    {
        $classes = Student::select('classroom', DB::raw('count(*) as total'))
                    ->groupBy('classroom')
                    ->get();
        return view('admin.students.index', compact('classes'));
    }

    // TAMPILKAN ISI KELAS (REGULER & KARYAWAN)
    public function showClass($classroom)
    {
        $reguler = Student::where('classroom', $classroom)->where('type', 'reguler')->with('user')->get();
        $karyawan = Student::where('classroom', $classroom)->where('type', 'karyawan')->with('user')->get();

        return view('admin.students.show', compact('classroom', 'reguler', 'karyawan'));
    }

    public function create()
    {
        $existingClasses = Student::select('classroom')->distinct()->pluck('classroom');
        return view('admin.students.create', compact('existingClasses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'nis' => 'required',
            'classroom' => 'required',
            'type' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'peserta',
        ]);

        Student::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'phone' => $request->phone,
            'classroom' => $request->classroom,
            'type' => $request->type,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Peserta berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // Student otomatis terhapus jika di-set cascade di DB
        return redirect()->back()->with('success', 'Data peserta dihapus.');
    }
}